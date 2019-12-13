<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

use App\Models\ReservedProducts;

use Input;
use Log;

class WooCommerceController extends Controller
{
    private static function get_woocommerce_client() {
        $config = [
            'store_url' => 'https://stores.allotment.pro/fulfilmentshoppy/',
            'consumer_key' => 'ck_d1a394997fdec3f4e84c963b7aac5a09bc423232',
            'consumer_secret' => 'cs_627c796fc6b1a0eb99f865514938d756a8b9843e',
            'verify_ssl' => false,
            'api_version' => 'v2',
            'wp_api' => true,
            'query_string_auth' => true,
            'timeout' => 60,
        ];

        $woocommerce = new Client(
        $config['store_url'],
        $config['consumer_key'],
        $config['consumer_secret'],
            [
                'version' => 'wc/' . $config['api_version'],
                'verify_ssl' => $config['verify_ssl'],
                'wp_api' => $config['wp_api'],
                'query_string_auth' => $config['query_string_auth'],
                'timeout' => $config['timeout'],
            ]
        );

        return $woocommerce;
    }

    public static function reserve_product_stock($product_data, $order_expires_time, $event_id) {
        $simple_products = $product_data[0];
        $variable_products = $product_data[1];

        //Process products
        $simple_products_data = self::processWoocommerceProducts($simple_products, 'simple');
        $variable_products_data = self::processWoocommerceProducts($variable_products, 'variable');

        // Log::info(print_r("''''''''''''''", true));
        // Log::info(print_r($variable_products_data, true));
        // Log::info(print_r("'''''''''''''''", true));

        // $simple_products = self::map_products_data($simple_products_data, $simple_products, 'simple');
        // $variable_products = self::map_products_data($variable_products_data, $variable_products, 'variable');

        // Log::info(print_r('---====----', true));
        // Log::info(print_r($variable_products, true));
        // Log::info(print_r('---====----', true));

        //If at this point everything should be good with WooCommerce so we update the Ticketing side
        foreach($simple_products as $simple_prod) {
            self::processTicketingProducts($simple_prod, $event_id, $order_expires_time);
        }

        foreach($variable_products as $simple_prod) {
            self::processTicketingProducts($simple_prod, $event_id, $order_expires_time);
        }
    }

    public static function map_products_data($products_data, $main_data, $type) {
        $return = [];

        foreach($main_data as $pkey => $data) {
            foreach($products_data as $key => $prod) {
                if($type == 'simple') {
                    if($data['product_id'] == $prod['product_id']) {
                        $entry = $data;
                        $entry['name'] = $prod['product_name'];
                        $entry['price'] = $prod['product_price'];

                        $return[] = $entry;

                        break;
                    }
                } elseif($type == 'variable') {
                    if($data['variation_id'] == $prod['product_variation_id']) {
                        $entry = $prod;

                        $return[] = $entry;

                        break;
                    }
                }
            }
        }

        // Log::info(print_r('.........', true));
        // Log::info(print_r($return, true));
        // Log::info(print_r('.......', true));

        return $return;
    }

    private static function processTicketingProducts($product, $event_id, $order_expires_time, $is_variation = false) {
        $reservedProducts = new reservedProducts();
        $reservedProducts->product_id = $product['product_id'];

        if($is_variation) {
            $reservedProducts->variation_id = $product['variation_id'];
        }

        $reservedProducts->event_id = $event_id;
        $reservedProducts->quantity_reserved = $product['value'];
        $reservedProducts->expires = $order_expires_time;
        $reservedProducts->session_id = session()->getId();
        $reservedProducts->save();
    }

    private static function processWoocommerceProducts($products_list, $products_type) {
        $woocommerce = self::get_woocommerce_client();
        $product_details = [];

        foreach($products_list as $single_prod) {
            try {
                if($products_type == 'simple') {
                    $product = $woocommerce->get('products/' . $single_prod['product_id']);

                    $entry = [
                        'product_id' => $single_prod['product_id'],
                        'product_name' => $product->name,
                        'product_price' => $product->price
                    ];
                } else {
                    $parent_product = $woocommerce->get('products/' . $single_prod['product_id']);
                    $product = $woocommerce->get('products/' . $single_prod['product_id'] . '/variations/' . $single_prod['variation_id']);
                    
                    $product_name = $parent_product->name;

                    (isset($parent_product->attributes[0]) ? $product_name .= ' (' . $product->attributes[0]->option . ')' : '');

                    $entry = [
                        'product_id' => $single_prod['product_id'],
                        'product_name' => $product_name,
                        'product_price' => $product->price,
                        'value' => $single_prod['value']
                    ];
                }

                if($products_type == 'simple') {
                    $entry['product_variation_id'] = 0;
                } else {
                    $entry['product_variation_id'] = $single_prod['variation_id'];
                }

                $product_details[] = $entry;
            } catch(HttpClientException $e) {
                echo $e;
                return false;
            }

            //Add old back stock in if it has already been saved on Ticketing side
            $current_reserved_products = reservedProducts::where('session_id', '=', session()->getId())->first();

            if($current_reserved_products) {
                $reserved_stock = $current_reserved_products->quantity_reserved;
                $old_stock_qty = $product->stock_quantity;
    
                $data = [
                    'manage_stock' => true,
                    'stock_quantity' => $old_stock_qty + $reserved_stock
                ];

                $new_total = $old_stock_qty + $reserved_stock;

                if($products_type == 'simple') {
                    $result = $woocommerce->put('products/' . $single_prod['product_id'], $data);
                } else {
                    $result = $woocommerce->put('products/' . $single_prod['product_id'] . '/variations/' . $single_prod['variation_id'], $data);
                }

                if(!$result) {
                    return false;
                }

                $current_reserved_products->delete();
            }

            // Remove the stock now
            if(isset($new_total)) {
                $data = [
                    'manage_stock' => true,
                    'stock_quantity' => $new_total - $single_prod['value']
                ];
            } else {
                $data = [
                    'manage_stock' => true,
                    'stock_quantity' => $product->stock_quantity - $single_prod['value']
                ];
            }

            if($products_type == 'simple') {
                $result = $woocommerce->put('products/' . $single_prod['product_id'], $data);
            } else {
                //$parent = $woocommerce->get('products/' . $single_prod['product_id'], $data);
                $result = $woocommerce->put('products/' . $single_prod['product_id'] . '/variations/' . $single_prod['variation_id'], $data);
            }
        }

        return $product_details;
    }

    public static function check_product_stock($prod, $product_type) {
        $woocommerce = self::get_woocommerce_client();

        if($product_type == 'simple') {
            try {
                $product = $woocommerce->get('products/' . $prod['product_id']);
            } catch(HttpClientException $e) {
                echo $e;
                return false;
            }
        } elseif($product_type == 'variable') {
            try {
                $product = $woocommerce->get('products/' . $prod['product_id'] . '/variations/' . $prod['variation_id']);
            } catch(HttpClientException $e) {
                echo $e;
                return false;
            }
        }

        if($product->manage_stock == 1) {
            $calculated_reserved_stock = $product->stock_quantity - $prod['value'];

            if($calculated_reserved_stock < 0) {
                if($product_type == 'simple') {
                    return 'The product "' . $product->name . '" does not have enough stock.<br/>';
                } elseif($product_type == 'variable') {
                    if(!isset($product->attributes[0])) {
                        return 'There is not enough stock.<br/>';
                    } else {
                        return 'There is not enough stock for ' . $product->attributes[0]->name . ' ' . $product->attributes[0]->option . '.<br/>';
                    }
                }
            }
        }

        return '';
    }

    public static function getShopProducts($product_ids) {

        $woocommerce = self::get_woocommerce_client();

        try {
            $products = $woocommerce->get('products');
        } catch(HttpClientException $e) {
            echo $e;
            return false;
        }
        
        $return_products = [];

        foreach($products as $product) {
            if(in_array($product->id, $product_ids)) {
                if($product->type == 'variable') {
                    $entry = self::map_variable_product_data($product, $woocommerce);
                    $return_products[] = $entry;
                } elseif($product->type == 'bundle') {
                    $entry = self::map_bundle_product_data($product, $products, $woocommerce);
                    $return_products[] = $entry;
                } else {
                    $entry = self::map_simple_product_data($product);
                    $return_products[] = $entry;
                }
            }
        }

        return $return_products;
    }

    private static function map_bundle_product_data($product, $products, $woocommerce) {
        $entry = [
            'id' => $product->id,
            'type' => 'bundle',
            'name' => $product->name
        ];

        if(isset($product->images[0])) {
            $entry['image'] = $product->images[0]->src;
        }

        $bundle_products = [];

        foreach($product->bundled_items as $prod) {

            $bundle_entry = [
                'id' => '',
                'type' => '',
                'name' => '',
                'image' => ''
            ];

            foreach($products as $product) {
                if($prod->product_id == $product->id) {
                    $bundle_entry['name'] = $product->name;
                    $bundle_entry['type'] = $product->type;
                    $bundle_entry['id'] = $product->id;

                    if(isset($product->images[0])) {
                        $bundle_entry['image'] = $product->images[0]->src;
                    }

                    if($bundle_entry['type'] == 'simple') {
                        $bundle_entry['price'] = '£' . $product->price;
                        $bundle_entry = self::determine_product_stock($bundle_entry, $product);
                    }

                    if($bundle_entry['type'] == 'variable') {
                        $bundle_var_product = self::map_variable_product_data($product, $woocommerce, true);
                        $bundle_entry = $bundle_var_product;
                    }

                    $bundle_products[] = $bundle_entry;

                    break;
                }
            }
        }

        $entry['products'] = $bundle_products;

        return $entry;
    }

    private static function map_variable_product_data($product, $woocommerce, $is_bundle = false) {
        $entry = [
            'id' => $product->id,
            'type' => 'variable',
            'name' => $product->name,
            'price_range' => '',
            'image' => '',
            'variations' => []
        ];

        if(isset($product->images[0])) {
            $entry['image'] = $product->images[0]->src;
        }

        $variation_products = [];
        $min_val = false;
        $max_val = false;

        foreach($product->variations as $var_id) {
            $prod = [
                'id' => $var_id,
                'name' => '',
                'stock_level' => '',
                'stock_amount' => '',
                'price' => ''
            ];

            $variation_data = $woocommerce->get('products/' . $product->id . '/variations/' . $var_id);

            if($min_val == false && $max_val == false) {
                $min_val = $variation_data->price;
                $max_val = $variation_data->price;
            } elseif($min_val != false && $max_val == false) {
                $max_val = $variation_data->price;
            } elseif($min_val != false && $max_val != false) {
                if($variation_data->price < $min_val) {
                    $min_val = $variation_data->price;
                } elseif($variation_data->price > $max_val) {
                    $max_val = $variation_data->price;
                }
            }
            
            if(isset($variation_data->attributes[0])) {
                $prod['name'] = $variation_data->attributes[0]->option;
            }

            $prod = self::determine_product_stock($prod, $variation_data);

            $prod['price'] = '£' . number_format($variation_data->price, 2, '.', '');

            if(isset($variation_data->image)) {
                $prod['image'] = $variation_data->image->src;
            }

            $variation_products[] = $prod;
        }

        if($min_val == $max_val) {
            $entry['price_range'] = '£' . $min_val;
        } else {
            $entry['price_range'] = '£' . $min_val . ' - £' . $max_val;
        }

        $entry['variations'] = $variation_products;

        return $entry;
    }

    private static function map_simple_product_data($product) {
        $entry = [
            'id' => $product->id,
            'type' => 'simple',
            'name' => $product->name,
            'stock_level' => '',
            'stock_amount' => '',
            'price' => '',
            'image' => ''
        ];

        $entry = self::determine_product_stock($entry, $product);

        $entry['price'] = '£' . $product->price;

        if(isset($product->images[0])) {
            $entry['image'] = $product->images[0]->src;
        }

        return $entry;
    }

    private static function determine_product_stock($entry, $product) {
        $entry = $entry;

        if($product->manage_stock == 0) {
            $entry['stock_level'] = 'available';  
            $entry['stock_amount'] = 'In Stock';       
        }

        if($product->manage_stock == 1 && $product->stock_quantity < 1 || $product->manage_stock == 1 && $product->stock_quantity == '' ) {
            $entry['stock_level'] = 'not_available';
            $entry['stock_amount'] = 'Out Of Stock';   
        }

        if($product->manage_stock == 1 && $product->stock_quantity > 0) {
            $entry['stock_level'] = 'available';
            $entry['stock_amount'] = $product->stock_quantity;  
        }

        return $entry;
    }

    public static function updateProductStock(Request $request) {
        $product_id = Input::get('product_id', '');
        $variation_id = Input::get('variation_id', '');
        $is_variation = Input::get('is_variation', '');
        $operator = Input::get('operator', '');
        $stock_qty = Input::get('stock_qty', '');

        $message = '<p>';

        // Basic validation
        if($product_id == '') {
            $message .= "There was no product ID specified.<br/>";
        }

        if($is_variation == true && $variation_id == '') {
            $message .= "No variation ID was specified.<br/>";
        }

        if($stock_qty == '') {
            $message .= "There was no quantity specified.<br/>";
        }

        // Process if validation passed
        if(
            (
            $product_id != '' &&
            $stock_qty != ''
            ) ||
            (
            $product_id != '' &&
            $variation_id != '' &&
            $is_variation != '' &&
            $stock_qty != ''
            )
        ) {
            $woocommerce = self::get_woocommerce_client();

            if($is_variation == true) {
                $product = $woocommerce->get('products/' . $product_id . '/variations/' . $variation_id);
            } else {
                $product = $woocommerce->get('products/' . $product_id);
            }

            $old_stock_qty = $product->stock_quantity;

            $data = [
                'manage_stock' => true,
                'stock_quantity' => $stock_qty
            ];

            if($is_variation == true) {
                $woocommerce->put('products/' . $product_id . '/variations/' . $variation_id, $data);
            } else {    
                $result = $woocommerce->put('products/' . $product_id, $data);
            }

            if($is_variation == true) {
                $message .= 'The product is a variation type.<br/>';
                $message .= 'The original stock of this product is: ' . $old_stock_qty . '<br/>';
                $message .= 'The product variation has changed the stock level to: ' . $stock_qty . '<br/>';
            } else {
                $message .= 'The product is a simple type.<br/>';
                $message .= 'The original stock of this product is: ' . $old_stock_qty . '<br/>';
                $message .= 'The product has changed the stock level to: ' . $stock_qty . '<br/>';
            }
        }

        $message .= '</p>';

        return view('Public.WooCommerceStock', [
            'data' => [
                'message' => $message
            ]
        ]);
    }
}
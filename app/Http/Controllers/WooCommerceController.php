<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

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

    public static function check_product_stock($prod, $product_type) {
        $woocommerce = self::get_woocommerce_client();

        if($product_type == 'simple') {
            try {
                $product = $woocommerce->get('products/' . $prod['product_id']);
            } catch(HttpClientException $e) {
                echo $e;
                return false;
            }

            if($product->manage_stock == 1) {
                $calculated_reserved_stock = $product->stock_quantity - $prod['value'];

                if($calculated_reserved_stock < 1) {
                    return 'The product "' . $product->name . '" does not have enough stock.<br/>';
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

        //Log::info(print_r($min_val, true));
        //Log::info(print_r($max_val, true));

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
}
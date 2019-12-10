<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

use Log;

class WoocommerceController extends Controller
{
    public function getShopProducts(Request $request) {

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

        try {
            $products = $woocommerce->get('products');
        } catch(HttpClientException $e) {
            echo $e;
            return false;
        }
        
        

        $shop_data = [
            'name' => 'Fulfilment Shoppy',
            'products' => $products
        ];

        Log::info(print_r($products, true));

        return view('Woocommerce.ShowShopProducts', ['shop_data' => $shop_data]);
    }
}

<?php

namespace Rez1pro\LaraPaypal\Core;

use Rez1pro\LaraPaypal\Auth\PaypalAuthentication;

class Product extends PaypalAuthentication
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createProduct($data)
    {
        // First create a product since PayPal requires a valid product_id
        $productResponse = $this->client->post(config('lara-paypal.base_url') . '/catalogs/products', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->tokenInstance->access_token,
            ],
            'json' => [
                'name' => $data['name'],
                'description' => $data['description'],
                'type' => 'SERVICE',
                'category' => 'SOFTWARE'
            ]
        ]);

        return json_decode($productResponse->getBody()->getContents());
    }

    public function updateProduct($productId, $description)
    {
        $response = $this->client->patch(config('lara-paypal.base_url') . '/catalogs/products/' . $productId, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->tokenInstance->access_token,
                'Accept' => 'application/json',
            ],
            'json' => [
                [
                    'op' => 'replace',
                    'path' => '/description',
                    'value' => $description
                ]
            ]
        ]);
        return json_decode($response->getBody()->getContents());
    }

    public function getProductList()
    {
        $response = $this->client->get(config('lara-paypal.base_url') . '/catalogs/products', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->tokenInstance->access_token,
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }
}

<?php

namespace Rez1pro\LaraPaypal\Auth;

use GuzzleHttp\Client;

class PaypalAuthentication
{
    protected $client;
    protected $tokenInstance;
    protected function __construct()
    {
        $this->client = new Client();

        $response = $this->client->post(config('lara-paypal.base_url') . '/oauth2/token', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ],
            'auth' => [
                config('lara-paypal.client_id'),
                config('lara-paypal.secret')
            ]
        ]);

        $this->tokenInstance = json_decode($response->getBody()->getContents());
    }
}

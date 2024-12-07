<?php

namespace Rez1pro\LaraPaypal\Core;

use Rez1pro\LaraPaypal\Auth\PaypalAuthentication;

class Plan extends PaypalAuthentication
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPlanList()
    {
        $response = $this->client->get(config('lara-paypal.base_url') . '/billing/plans', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->tokenInstance->access_token,
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }

    public function showPlan($planId)
    {
        $response = $this->client->get(config('lara-paypal.base_url') . '/billing/plans/' . $planId, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->tokenInstance->access_token,
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }
}

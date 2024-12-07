<?php

namespace Rez1pro\LaraPaypal\Core;

use Rez1pro\LaraPaypal\Auth\PaypalAuthentication;


class Subscription extends PaypalAuthentication
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

    /**
     * Create a subscription plan in PayPal
     *
     * @param array $data Array containing plan details:
     *   - name: string - Name of the subscription plan
     *   - description: string - Description of the subscription plan
     *   - interval_unit: string - Unit for billing cycle (DAY, WEEK, MONTH, YEAR)
     *   - interval_count: int - Number of interval units between billings
     *   - total_cycles: int - Total number of cycles, 0 for infinite (optional)
     *   - price: float - Price per billing cycle
     *   - currency_code: string - Currency code (optional, defaults to USD)
     *   - payment_preferences: array (optional)
     *     - setup_fee: array
     *       - value: string - Setup fee amount (optional, defaults to 0)
     * @param string $productId PayPal product ID
     * @return object PayPal API response
     */
    public function createSubscriptionPlan($data, $productId)
    {
        // Then create the subscription plan with the product ID
        $response = $this->client->post(config('lara-paypal.base_url') . '/billing/plans', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->tokenInstance->access_token,
            ],
            'json' => [
                'product_id' => $productId,
                'name' => $data['name'],
                'description' => $data['description'],
                'billing_cycles' => [
                    [
                        'frequency' => [
                            'interval_unit' => $data['interval_unit'],
                            'interval_count' => $data['interval_count']
                        ],
                        'tenure_type' => 'REGULAR',
                        'sequence' => 1,
                        'total_cycles' => $data['total_cycles'] ?? 0,
                        'pricing_scheme' => [
                            'fixed_price' => [
                                'value' => $data['price'],
                                'currency_code' => $data['currency_code'] ?? 'USD'
                            ]
                        ]
                    ]
                ],
                'payment_preferences' => [
                    'auto_bill_outstanding' => true,
                    'setup_fee' => [
                        'value' => $data['payment_preferences']['setup_fee']['value'] ?? '0',
                        'currency_code' => $data['currency_code'] ?? 'USD'
                    ],
                    'setup_fee_failure_action' => 'CONTINUE',
                    'payment_failure_threshold' => 3
                ]
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }

    public function getPlan($planId)
    {
        $response = $this->client->get(config('lara-paypal.base_url') . '/billing/plans/' . $planId, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->tokenInstance->access_token,
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }

    public function payForSubcription($planId, $data = [])
    {
        $response = $this->client->post(config('lara-paypal.base_url') . '/billing/subscriptions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->tokenInstance->access_token,
            ],
            'json' => [
                'plan_id' => $planId,
                'custom_id' => $data['model_id'] ?? '',
                'subscriber' => [
                    'name' => [
                        'given_name' => $data['first_name'] ?? '',
                        'surname' => $data['last_name'] ?? ''
                    ],
                    'email_address' => $data['email'] ?? '',
                ],
                'application_context' => [
                    'user_action' => 'SUBSCRIBE_NOW',
                    'return_url' => $data['return_url'] ?? config('lara-paypal.subscription_return_callback'),
                    'cancel_url' => $data['cancel_url'] ?? config('lara-paypal.subscription_cancel_callback'),
                    'brand_name' => $data['brand_name'] ?? config('app.name'),
                    'shipping_preference' => 'NO_SHIPPING',
                    'payment_method' => [
                        'payer_selected' => 'PAYPAL',
                        'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED'
                    ]
                ],
            ]
        ]);

        $result = json_decode($response->getBody()->getContents());

        // Redirect to PayPal checkout
        if (isset($result->links)) {
            foreach ($result->links as $link) {
                if ($link->rel == 'approve') {
                    return redirect($link->href);
                }
            }
        }

        return $result;
    }

    public function getSubscription($subscriptionId)
    {
        $response = $this->client->get(config('lara-paypal.base_url') . '/billing/subscriptions/' . $subscriptionId, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->tokenInstance->access_token,
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }

    public function cancelSubscription($subscriptionId, $reason = '')
    {
        $response = $this->client->post(config('lara-paypal.base_url') . '/billing/subscriptions/' . $subscriptionId . '/cancel', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->tokenInstance->access_token,
            ],
            'json' => [
                'reason' => $reason
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }

    public function getSubscriptionDetails($subscriptionId)
    {
        $response = $this->client->get(config('lara-paypal.base_url') . '/billing/subscriptions/' . $subscriptionId, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->tokenInstance->access_token,
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }
    public function getSubscriptionTransactions($subscriptionId)
    {
        $startTime = request('start_time', '2020-01-01T00:00:00.000Z');
        $endTime = request('end_time', date('Y-m-d\TH:i:s\Z'));

        $response = $this->client->get(config('lara-paypal.base_url') . '/billing/subscriptions/' . $subscriptionId . '/transactions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->tokenInstance->access_token,
            ],
            'query' => [
                'start_time' => $startTime,
                'end_time' => $endTime
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }
}

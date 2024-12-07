# **Lara-PayPal**

`lara-paypal` is a simple and developer-friendly package to integrate PayPal payment processing into your Laravel application. This package supports subscriptions and payment processing through PayPal's API.

## **Table of Contents**
- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Subscriptions](#subscriptions)
- [License](#license)

## **Features**
- 🔌 Seamless integration with PayPal API
- 🔄 Subscription management
- 🏗️ Sandbox environment support
- ⚡ Simple and intuitive API

## **Installation**

```
composer require rez1pro/lara-paypal
```

1. Add the following environment variables to your `.env` file:
``` 
LARA_PAYPAL_CLIENT_ID=your-paypal-client-id
LARA_PAYPAL_SECRET=your-paypal-secret
LARA_PAYPAL_MODE=sandbox # or live
LARA_PAYPAL_SUBSCRIPTION_CANCEL_CALLBACK=https://your-domain.com/cancel-callback
LARA_PAYPAL_SUBSCRIPTION_RETURN_CALLBACK=https://your-domain.com/return-callback
PAYPAL_INITIAL_PLAN_ID=your-plan-id # Required for subscriptions
```

2. Publish the configuration file:
```
php artisan vendor:publish --provider="LaraPaypal\LaraPaypalServiceProvider"
```

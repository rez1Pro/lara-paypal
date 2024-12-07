<?php

namespace Rez1pro\LaraPaypal;

use Illuminate\Support\ServiceProvider;

class LaraPaypalServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/lara-paypal.php' => config_path('lara-paypal.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/lara-paypal.php', 'lara-paypal');
    }
}

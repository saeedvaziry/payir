<?php

namespace SaeedVaziry\Payir;

use Illuminate\Support\ServiceProvider;

class PayirServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/payir.php', 'payir'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/payir.php' => config_path('payir.php'),
        ]);
    }
}

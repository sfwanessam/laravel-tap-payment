<?php

namespace Essam\TapPayment;
use Illuminate\Support\ServiceProvider;

class TapServiceProvider extends ServiceProvider
{

  /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/tap_payment.php' => config_path('tap_payment.php'),
        ], 'tap_payment-config');

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $packageConfigFile = __DIR__.'/../config/tap_payment.php';

        $this->mergeConfigFrom(
            $packageConfigFile, 'tap_payment'
        );

        //$this->registerBindings();
    }


    /**
     * Registers app bindings and aliases.
     */
    protected function registerBindings()
    {
        $this->app->singleton(Payment::class, function () {
            return new Payment();
        });

        $this->app->alias(Payment::class, 'Payment');
    }
}

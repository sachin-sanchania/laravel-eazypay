<?php

namespace SachinSanchania\LaravelEazypay\Providers;

use Illuminate\Support\ServiceProvider;
use SachinSanchania\LaravelEazypay;

class EazypayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../../config/eazypay.php' => config_path('eazypay.php')], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $config = __DIR__ . '/../../config/eazypay.php';
        $this->mergeConfigFrom($config, 'eazypay');
        $this->app->singleton('LaravelEazypay', LaravelEazypay::class);
    }
    public function provides()
    {
        // return ['Eazypay'];
    }
}

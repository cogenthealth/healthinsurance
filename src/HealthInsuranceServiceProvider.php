<?php

namespace CogentHealth\Healthinsurance;

use Illuminate\Support\ServiceProvider;

class HealthInsuranceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/api.php', 'hi_api_url');
        $this->mergeConfigFrom(__DIR__ . '/config/auth.php', 'hi_auth');
        $this->app->singleton(HealthInsurance::class, function ($app) {
            return new HealthInsurance($app->make(HealthInsurance::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->publishes([
            __DIR__ . '/database/migrations' => base_path('database/migrations/'),
        ], 'migrations');
    }
}

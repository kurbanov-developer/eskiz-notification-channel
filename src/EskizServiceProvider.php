<?php

namespace KurbanovDeveloper\EskizNotificationChannel;

use Illuminate\Support\ServiceProvider;

class EskizServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/eskiz.php' => config_path('eskiz.php'),
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/eskiz.php', 'eskiz');
    }

    public function register()
    {
        $this->app->singleton(EskizClient::class, function ($app) {
            return new EskizClient(
                config('eskiz.email'),
                config('eskiz.password')
            );
        });
    }
}

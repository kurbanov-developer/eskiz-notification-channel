<?php

namespace Vendor\EskizNotificationChannel;

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
        //
    }
}

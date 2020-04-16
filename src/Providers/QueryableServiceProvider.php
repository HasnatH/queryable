<?php

namespace HasnatH\Queryable\Providers;

use Illuminate\Support\ServiceProvider;

class QueryableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/queryable.php' => config_path('queryable.php'),
            ], 'queryable-config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/queryable.php', 'queryable');
    }
}
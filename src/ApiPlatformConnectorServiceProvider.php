<?php

namespace Go2Flow\ApiPlatformConnector;

use Illuminate\Support\ServiceProvider;

class ApiPlatformConnectorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ]);

    }
}

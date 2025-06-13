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
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $loader->alias(
            'Api',
            'Go2Flow\ApiPlatformConnector\Api\Api'
        );
    }
}

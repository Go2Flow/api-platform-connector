<?php

namespace App\Api;

use App\Api\Services\ApiService;
use Go2Flow\ApiPlatformConnector\Api\Authenticators\StandardAuth;
use Go2Flow\ApiPlatformConnector\Models\ApiAuth;
use Go2Flow\ApiPlatformConnector\Api\Authenticators\Interfaces\AuthInterface;

class Api
{
    /**
     * Helper class for creating ApiService instances with specific ApiAuth.
     * Not a Laravel Facade.
     */

    public static function make(ApiAuth|AuthInterface $apiAuth) : ApiService
    {
        return $apiAuth instanceof ApiAuth
            ? new ApiService(new StandardAuth($apiAuth))
            : new ApiService($apiAuth);
    }
}

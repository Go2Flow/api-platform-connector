<?php

namespace Go2Flow\ApiPlatformConnector\Api\Authenticators\Interfaces;
use GuzzleHttp\Client as Guzzle;


interface AuthInterface
{
    public function authenticate(Guzzle $client) : ?string;

    public function url() : string;

    public function payload() : array;
}

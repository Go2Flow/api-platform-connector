<?php

namespace Go2Flow\ApiPlatformConnector\Api\Authenticators;

use Go2Flow\ApiPlatformConnector\Api\Authenticators\Interfaces\AuthInterface;
use Go2Flow\ApiPlatformConnector\Models\ApiAuth;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Support\Facades\Crypt;

class StandardAuth implements AuthInterface
{
    public function __construct(private ApiAuth $auth) {}

    public function authenticate(Guzzle $client) : ?string {

        if ($this->auth->token) return $this->auth->token;

        $password = Crypt::decryptString($this->auth->password);

        $this->response = $this->guzzleClient->get(
            'api-platform/auth?email=' . $this->auth->user_name . '&password=' . $password,
            [
                'headers' => ['Content-Type' => 'application/json'],
            ]
        );

        if ($this->response->getStatusCode() !== 200) return false;

        $token = json_decode($this->response->getBody())->token;

        $this->auth->update([
            'token' => $token
        ]);

        return $token;
    }

    public function url() : string
    {
        return $this->auth->url;
    }

    public function token() : string
    {
        return $this->auth->token;
    }
}

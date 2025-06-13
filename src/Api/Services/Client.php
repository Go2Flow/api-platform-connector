<?php

namespace App\Api\Services;

use App\Models\ApiAuth;
use Go2Flow\ApiPlatformConnector\Api\Authenticators\Interfaces\AuthInterface;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Client
{
    private array $payload = [];
    private Guzzle $guzzleClient;

    private ?array $parameters = [];


    public function __construct(private AuthInterface $auth) {

        $this->guzzleClient = new Guzzle(['base_uri' => $this->auth->url()]);
    }

    public function authenticate() : self
    {
        $this->auth->authenticate($this->guzzleClient);

        return $this;

    }

    public function addParameter(string $parameter) : void
    {
        $this->parameters[] = $parameter;
    }

    public function sendRequest($path, $method = 'GET', $content = null) : ?object
    {

        try {
            $response = $this->guzzleClient->request(
                $method,
                'api-platform/' . $path . $this->addParameters(),
                $content ?: $this->setPayload()
            );

        } catch (ClientException $e) {
           Log::info($e->getMessage());
        }

        $this->clearPayload();


        return $response ?? null;
    }

        public function clearPayload() : self
    {
        $this->payload = [];
        $this->parameters = [];

        return $this;
    }

    private function addParameters() : string
    {
        if (count($this->parameters) == 0) return '';

        $parameters = Str::of('?');

        foreach ($this->parameters as $parameter) {

            $parameters = $parameters->append( $parameter . '&');
        }

        return $parameters->rtrim('&')->toString();
    }

    private function setPayload() : array
    {

        $payload = $this->auth->payload();

        foreach ($this->payload as $key => $content) {
            $payload[$key] = json_encode($content);
        }

        return $payload;
    }

    public function addToPayload(string|array $array, string $field = 'body') : self
    {
        if (isset($this->payload[$field])) {
            $array += $this->payload[$field];
        }

        $this->payload[$field] = $array;

        return $this;
    }
}

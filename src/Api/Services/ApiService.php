<?php

namespace Go2Flow\ApiPlatformConnector\Api\Services;

use Go2Flow\ApiPlatformConnector\Api\Authenticators\Interfaces\AuthInterface;
use Illuminate\Support\Str;
use RuntimeException;

class ApiService
{

    protected ?Response $response = null;
    protected ?string $path;
    protected Client $client;

    public function __construct(AuthInterface $auth) {

        $this->client = new Client($auth);

        $this->client->authenticate();
    }

    protected function getRequest(): self
    {
        $this->response = $this->client
            ->sendRequest(
                $this->path
            );
        return $this;
    }

    protected function patchRequest(array $payload, string|int $id): self
    {
        $this->response = $this->client
            ->addToPayload($payload)
            ->sendRequest(
                $this->path . '/' . $id,
                'PATCH'
            );

        return $this;
    }

    protected function postRequest(array $payload): self
    {
        $this->response = $this->client
            ->addToPayload($payload)
            ->sendRequest(
                $this->path,
                'POST'
            );

        return $this;
    }

    public function filter(array $filter) : self
    {
        $key = array_key_first($filter);
        $value = $filter[$key];

        $this->createFilter($key, $value);

        return $this;
    }

    public function limit(int $limit): self
    {

        $this->client->addParameter('per_page=' . $limit);

        return $this;
    }

    public function page(int $page): self
    {
        $this->client->addParameter('page=' . $page);

        return $this;
    }

    public function relations(array $relations) : self
    {
        $include = Str::of('include=');

        foreach ($relations as $relation) {

            $include = $include->append($relation . ',');
        }

        $this->client->addParameter( $include->rtrim(',')->toString());

        return $this;
    }

    public function filters(array $filters) : self
    {
        foreach ($filters as $key => $value) {
            $this->createFilter($key, $value);
        }

        return $this;
    }

    public function get() : self
    {
        return $this->getRequest();
    }

    public function find(int $id) : self
    {
        $this->path .= '/' . $id;
        return $this->getRequest();
    }

    public function patch(int $id, array $payload) : self
    {
        return $this->patchRequest(
            $payload,
            $id
        );
    }

    public function post(array $payload) : self
    {
        return $this->postRequest($payload);
    }

    public function body(): ?object
    {
        try {
            return $this->response->data();
        }
        catch (\Exception $e) {
            throw new RuntimeException ('There is no response object. Did you send a request?');
        }
    }

    public function status(): int
    {
        try {
            return $this->response->status();
        }
        catch (\Exception $e) {
            throw new RuntimeException ('There is no response object. Did you send a request?');
        }
    }

    public function error(): ?string
    {
        try {
            return $this->response->error();
        }
        catch (\Exception $e) {
            throw new RuntimeException ('There is no response object. Did you send a request?');
        }
    }

    protected function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    private function createFilter($key, $value) : void {

        $this->client->addParameter("filter[$key]=$value");
    }

    public function __call(string $method, ?array $args): self
    {
        if (method_exists($this, $method)) {
            return $this->{$method}(...$args);
        }

        $this->setPath(Str::kebab($method));

        return $this;
    }
}

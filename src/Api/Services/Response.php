<?php

namespace Go2Flow\ApiPlatformConnector\Api\Services;

class Response
{
    public function __construct(
        private readonly int $status,
        private readonly ?object $data = null,
        private readonly ?string $error = null,
    ) {}

    public function status(): int
    {
        return $this->status;
    }

    public function data(): ?object
    {
        return $this->data;
    }

    public function error(): ?string
    {
        return $this->error;
    }
}

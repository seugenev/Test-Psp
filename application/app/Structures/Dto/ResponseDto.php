<?php

namespace App\Structures\Dto;

use Illuminate\Contracts\Support\Arrayable;

class ResponseDto implements Arrayable
{
    protected array $headers = [];
    protected array $payload = [];
    protected string $url = '';

    public static function make(): static
    {
        return new static();
    }

    public function headers(array $headers): static
    {
        $this->headers = $headers;
        return $this;
    }

    public function payload(array $payload): static
    {
        $this->payload = $payload;
        return $this;
    }

    public function url(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getUrl(): string
    {
        return $this->url;
    }


    #[\Override]
    public function toArray(): array
    {
        return [
            'headers' => $this->getHeaders(),
            'payload' => $this->getPayload(),
            'url' => $this->getUrl(),
        ];
    }
}

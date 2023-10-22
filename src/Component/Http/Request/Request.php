<?php

declare(strict_types=1);

namespace Component\Http\Request;

use Component\Http\Enum\Method;
use Component\Http\Operator\RequestBodyInterface;
use Component\Http\Operator\RequestInterface;
use Component\Http\Operator\UriInterface;

final readonly class Request implements RequestInterface
{
    /**
     * @param array<string, array<string>|string> $headers
     * @param array<string, mixed> $options
     */
    public function __construct(
        private UriInterface $uri,
        private Method $method = Method::GET,
        private ?RequestBodyInterface $body = null,
        private ?array $headers = [],
        private ?array $options = [],
    ) {
    }

    public function uri(): UriInterface
    {
        return $this->uri;
    }

    public function method(): Method
    {
        return $this->method;
    }

    public function body(): ?RequestBodyInterface
    {
        return $this->body;
    }

    /**
     * @return array<string, array<string>|string>
     */
    public function headers(): array
    {
        return $this->headers ?? [];
    }

    /**
     * @return array<string, mixed>
     */
    public function options(): array
    {
        return $this->options ?? [];
    }
}

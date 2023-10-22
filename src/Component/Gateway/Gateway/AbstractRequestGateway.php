<?php

declare(strict_types=1);

namespace Component\Gateway\Gateway;

use Component\Gateway\Exception\ClientResponseTransformerException;
use Component\Gateway\Exception\HttpClientException;
use Component\Gateway\Operator\GatewayInterface;
use Component\Gateway\Operator\RequestInterface;
use Component\Gateway\Operator\ResponseInterface;
use Component\Gateway\Response\Transformer\ClientResponseTransformerInterface;
use Component\Http\Enum\Method;
use Component\Http\Operator\HttpClientInterface;
use Component\Http\Operator\RequestBodyInterface;
use Component\Http\Operator\UriInterface;
use Component\Http\Request\Request;
use Throwable;

abstract readonly class AbstractRequestGateway implements GatewayInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private ClientResponseTransformerInterface $clientResponseTransformer,
    ) {
    }

    public function request(RequestInterface $request): ResponseInterface
    {
        $clientRequest = new Request(
            $this->uri($request),
            $this->method($request),
            $this->body($request),
            $this->headers($request),
            $this->options($request),
        );

        try {
            $response = $this->client->send($clientRequest);
        } catch (Throwable $e) {
            throw new HttpClientException($e);
        }

        if (null === $response = $this->clientResponseTransformer->transform($response)) {
            throw new ClientResponseTransformerException('Client response transformer returned null.');
        }

        return $response;
    }

    abstract protected function uri(RequestInterface $request): UriInterface;

    abstract protected function body(RequestInterface $request): ?RequestBodyInterface;

    abstract protected function method(RequestInterface $request): Method;

    /**
     * @return array<string, array<string>|string>|null
     */
    protected function headers(RequestInterface $request): ?array
    {
        return null;
    }

    /**
     * @return mixed[]|null
     */
    protected function options(RequestInterface $request): ?array
    {
        return null;
    }
}

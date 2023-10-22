<?php

declare(strict_types=1);

namespace Component\Http\Bridge\GuzzleHttp\Client;

use Component\Http\Bridge\Psr7\Transformer\UriTransformerInterface;
use Component\Http\Exception\HttpClientException;
use Component\Http\Operator\HttpClientInterface;
use Component\Http\Operator\RequestInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

final class Client implements HttpClientInterface
{
    private ClientInterface $client;
    private UriTransformerInterface $uriTransformer;

    public function __construct(
        ClientInterface $client,
        UriTransformerInterface $uriTransformer,
    ) {
        $this->client = $client;
        $this->uriTransformer = $uriTransformer;
    }

    public function send(RequestInterface $request): ResponseInterface
    {
        $guzzleRequest = new Request(
            $request->method()->value,
            $this->uriTransformer->transform($request->uri()),
            $request->headers(),
            (null !== $body = $request->body()) ? $body->toStream() : null,
        );

        try {
            return $this->client->send($guzzleRequest, $request->options());
        } catch (GuzzleException $e) {
            throw new HttpClientException($e->getMessage(), $e->getCode(), $e);
        }
    }
}

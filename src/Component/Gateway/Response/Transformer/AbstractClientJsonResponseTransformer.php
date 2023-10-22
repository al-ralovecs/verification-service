<?php

declare(strict_types=1);

namespace Component\Gateway\Response\Transformer;

use Component\Gateway\Exception\ClientResponseTransformerException;
use Component\Gateway\Operator\ResponseInterface;
use Psr\Http\Message\ResponseInterface as ClientResponse;
use Throwable;

abstract readonly class AbstractClientJsonResponseTransformer implements ClientResponseTransformerInterface
{
    public function transform(ClientResponse $response): ResponseInterface
    {
        try {
            $data = \json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            throw ClientResponseTransformerException::byBodyAndException((string) $response->getBody(), $e);
        }

        return $this->parseResponseData($data);
    }

    abstract protected function parseResponseData(mixed $data): ResponseInterface;
}

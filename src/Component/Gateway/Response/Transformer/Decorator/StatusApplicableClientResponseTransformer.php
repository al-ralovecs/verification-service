<?php

declare(strict_types=1);

namespace Component\Gateway\Response\Transformer\Decorator;

use Component\Gateway\Operator\ResponseInterface;
use Component\Gateway\Response\Transformer\ClientResponseTransformerInterface;
use Psr\Http\Message\ResponseInterface as ClientResponse;

final readonly class StatusApplicableClientResponseTransformer implements ClientResponseTransformerInterface
{
    public function __construct(
        private ClientResponseTransformerInterface $decoratedClientResponseTransformer,
        private int $status,
    ) {
    }

    public function transform(ClientResponse $response): ?ResponseInterface
    {
        if ($this->status !== $response->getStatusCode()) {
            return null;
        }

        return $this->decoratedClientResponseTransformer->transform($response);
    }
}

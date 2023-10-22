<?php

declare(strict_types=1);

namespace Component\Gateway\Response\Transformer;

use Component\Gateway\Operator\ResponseInterface;
use Psr\Http\Message\ResponseInterface as ClientResponse;

interface ClientResponseTransformerInterface
{
    public function transform(ClientResponse $response): ?ResponseInterface;
}

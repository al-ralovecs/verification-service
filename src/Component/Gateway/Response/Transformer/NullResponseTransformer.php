<?php

declare(strict_types=1);

namespace Component\Gateway\Response\Transformer;

use Component\Gateway\Response\NullResponse;
use Psr\Http\Message\ResponseInterface;

final readonly class NullResponseTransformer implements ClientResponseTransformerInterface
{
    public function transform(ResponseInterface $response): NullResponse
    {
        return new NullResponse();
    }
}

<?php

declare(strict_types=1);

namespace Component\Notification\Bridge\Gateway\Template;

use Component\Gateway\Operator\ResponseInterface;
use Component\Gateway\Response\Transformer\ClientResponseTransformerInterface;
use Psr\Http\Message\ResponseInterface as ClientResponse;

final readonly class ResponseTransformer implements ClientResponseTransformerInterface
{
    public function transform(ClientResponse $response): ?ResponseInterface
    {
        $data = $response->getBody()->getContents();

        return new Response($data);
    }
}

<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Message;

use Component\Gateway\Operator\ResponseInterface;
use Component\Gateway\Response\Transformer\AbstractClientJsonResponseTransformer;
use Component\Gotify\Dto\MessageDto;

final readonly class ResponseTransformer extends AbstractClientJsonResponseTransformer
{
    protected function parseResponseData(mixed $data): ResponseInterface
    {
        assert(is_array($data));

        return new Response(new MessageDto($data));
    }
}

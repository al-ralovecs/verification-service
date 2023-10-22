<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Application\Create;

use Component\Gateway\Operator\ResponseInterface;
use Component\Gateway\Response\Transformer\AbstractClientJsonResponseTransformer;
use Component\Gotify\Dto\ApplicationDto;

final readonly class ResponseTransformer extends AbstractClientJsonResponseTransformer
{
    protected function parseResponseData(mixed $data): ResponseInterface
    {
        assert(is_array($data));

        return new Response(new ApplicationDto($data));
    }
}

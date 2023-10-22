<?php

declare(strict_types=1);

namespace Component\Gateway\Exception;

use Throwable;

final class HttpClientException extends GatewayException
{
    public function __construct(Throwable $e)
    {
        parent::__construct($e->getMessage(), $e->getCode(), $e);
    }
}

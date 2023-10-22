<?php

declare(strict_types=1);

namespace Component\Gateway\Exception;

final class InvalidRequestException extends GatewayException
{
    public function __construct(string $expected, string $actual)
    {
        parent::__construct(sprintf('Invalid request. Expected %s, got %s.', $expected, $actual));
    }
}

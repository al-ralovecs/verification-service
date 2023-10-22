<?php

declare(strict_types=1);

namespace Component\Gateway\Exception;

use Throwable;

final class ClientResponseTransformerException extends GatewayException
{
    public static function byBodyAndException(string $responseBody, Throwable $e): self
    {
        return new self(
            sprintf('%s. Response: %s', rtrim($e->getMessage(), '.'), $responseBody),
            $e->getCode(),
            $e,
        );
    }
}

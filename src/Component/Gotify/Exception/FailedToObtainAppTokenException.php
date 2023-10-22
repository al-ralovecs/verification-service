<?php

declare(strict_types=1);

namespace Component\Gotify\Exception;

use RuntimeException;

final class FailedToObtainAppTokenException extends RuntimeException
{
    private const MESSAGE = 'Failed to obtain application token for recipient "%s".';

    public function __construct(string $recipient)
    {
        parent::__construct(sprintf(self::MESSAGE, $recipient));
    }
}

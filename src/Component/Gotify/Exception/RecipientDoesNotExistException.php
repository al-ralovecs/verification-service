<?php

declare(strict_types=1);

namespace Component\Gotify\Exception;

use RuntimeException;

final class RecipientDoesNotExistException extends RuntimeException
{
    private const MESSAGE = 'Recipient "%s" does not exist.';

    public function __construct(string $recipient)
    {
        parent::__construct(sprintf(self::MESSAGE, $recipient));
    }
}

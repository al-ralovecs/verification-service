<?php

declare(strict_types=1);

namespace Component\Notification\Exception;

use RuntimeException;

final class CannotDispatchNotificationException extends RuntimeException
{
    private const MESSAGE = 'Notification "%s" already dispatched';

    public static function alreadyDispatched(string $notificationId): self
    {
        return new self(sprintf(self::MESSAGE, $notificationId));
    }
}

<?php

declare(strict_types=1);

namespace Component\Notification\Exception;

use RuntimeException;
use Symfony\Component\Messenger\Exception\UnrecoverableExceptionInterface;

final class NotificationNotFoundException extends RuntimeException implements UnrecoverableExceptionInterface
{
    private const MESSAGE = 'Notification "%s" not found';

    public static function missingId(string $notificationId): self
    {
        return new self(sprintf(self::MESSAGE, $notificationId));
    }
}

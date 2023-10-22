<?php

declare(strict_types=1);

namespace Component\MessageRecorder\Exception;

use RuntimeException;

final class MessageBusNotResolvedException extends RuntimeException
{
    public function __construct(object $messageType)
    {
        parent::__construct(sprintf(
            'Cannot resolve message bus for message of type "%s".',
            $messageType::class,
        ));
    }
}

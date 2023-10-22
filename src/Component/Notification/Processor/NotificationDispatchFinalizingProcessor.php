<?php

declare(strict_types=1);

namespace Component\Notification\Processor;

use Component\Notification\Model\NotificationInterface;

final readonly class NotificationDispatchFinalizingProcessor implements NotificationProcessorInterface
{
    public function __invoke(NotificationInterface $notification): void
    {
        $notification->dispatch();
    }
}

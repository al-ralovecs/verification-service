<?php

declare(strict_types=1);

namespace Component\Notification\Processor\Decorator;

use Component\Notification\Model\NotificationInterface;
use Component\Notification\Processor\NotificationProcessorInterface;
use Component\Notification\Processor\NotificationProcessorMapInterface;

final readonly class DelegateNotificationProcessor implements NotificationProcessorInterface
{
    public function __construct(
        private NotificationProcessorMapInterface $dispatcherMap,
    ) {
    }

    public function __invoke(NotificationInterface $notification): void
    {
        $notificationDispatcher = $this->dispatcherMap->dispatcher($notification->channel());

        ($notificationDispatcher)($notification);
    }
}

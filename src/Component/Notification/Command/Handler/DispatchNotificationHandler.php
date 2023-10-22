<?php

declare(strict_types=1);

namespace Component\Notification\Command\Handler;

use Component\Notification\Command\DispatchNotificationCommand;
use Component\Notification\Operator\NotificationFetcherInterface;
use Component\Notification\Processor\NotificationProcessorInterface;

final readonly class DispatchNotificationHandler
{
    public function __construct(
        private NotificationFetcherInterface $notificationFetcher,
        private NotificationProcessorInterface $notificationProcessor,
    ) {
    }

    public function __invoke(DispatchNotificationCommand $command): void
    {
        $notification = $this->notificationFetcher->getById($command->id());

        ($this->notificationProcessor)($notification);
    }
}

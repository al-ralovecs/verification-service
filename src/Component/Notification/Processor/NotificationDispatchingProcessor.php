<?php

declare(strict_types=1);

namespace Component\Notification\Processor;

use Component\Notification\Dispatcher\NotificationDispatcherInterface;
use Component\Notification\Model\NotificationInterface;
use LogicException;

final readonly class NotificationDispatchingProcessor implements NotificationProcessorInterface
{
    public function __construct(
        private NotificationDispatcherInterface $notificationDispatcher,
    ) {
    }

    public function __invoke(NotificationInterface $notification): void
    {
        if (null === $notification->body()->content()) {
            throw new LogicException('Notification body content should be present by this moment.');
        }

        ($this->notificationDispatcher)($notification->recipient(), $notification->body()->content());
    }
}

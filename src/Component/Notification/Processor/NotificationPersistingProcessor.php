<?php

declare(strict_types=1);

namespace Component\Notification\Processor;

use Component\Notification\Model\NotificationInterface;
use Component\Notification\Operator\NotificationPersisterInterface;

final readonly class NotificationPersistingProcessor implements NotificationProcessorInterface
{
    public function __construct(
        private NotificationPersisterInterface $notificationPersister,
    ) {
    }

    public function __invoke(NotificationInterface $notification): void
    {
        $this->notificationPersister->save($notification);
    }
}

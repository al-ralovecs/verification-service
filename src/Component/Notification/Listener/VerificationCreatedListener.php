<?php

declare(strict_types=1);

namespace Component\Notification\Listener;

use Component\Notification\Command\CreateNotificationCommand;
use Component\Notification\Model\Notification;
use Component\Notification\Processor\NotificationProcessorInterface;
use Component\Verification\Event\VerificationCreatedEvent;

final readonly class VerificationCreatedListener
{
    public function __construct(
        private NotificationProcessorInterface $notificationProcessor,
    ) {
    }

    public function __invoke(VerificationCreatedEvent $event): void
    {
        $command = CreateNotificationCommand::fromEvent($event);
        $notification = Notification::create($command);

        ($this->notificationProcessor)($notification);
    }
}

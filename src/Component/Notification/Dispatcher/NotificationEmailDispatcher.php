<?php

declare(strict_types=1);

namespace Component\Notification\Dispatcher;

use Component\Notification\Sender\NotificationEmailSenderInterface;

final readonly class NotificationEmailDispatcher implements NotificationDispatcherInterface
{
    public function __construct(
        private NotificationEmailSenderInterface $notificationEmailSender,
    ) {
    }

    public function __invoke(string $recipient, string $content): void
    {
        $this->notificationEmailSender->send($recipient, $content);
    }
}

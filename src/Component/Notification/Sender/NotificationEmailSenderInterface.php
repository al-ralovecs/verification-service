<?php

declare(strict_types=1);

namespace Component\Notification\Sender;

interface NotificationEmailSenderInterface
{
    public function send(string $recipient, string $content): void;
}

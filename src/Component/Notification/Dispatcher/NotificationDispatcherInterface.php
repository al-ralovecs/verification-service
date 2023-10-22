<?php

declare(strict_types=1);

namespace Component\Notification\Dispatcher;

interface NotificationDispatcherInterface
{
    public function __invoke(string $recipient, string $content): void;
}

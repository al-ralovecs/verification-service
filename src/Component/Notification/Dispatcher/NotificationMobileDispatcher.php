<?php

declare(strict_types=1);

namespace Component\Notification\Dispatcher;

use Component\Gotify\Command\InitializeMessageSendingCommand;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class NotificationMobileDispatcher implements NotificationDispatcherInterface
{
    public function __construct(
        private MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(string $recipient, string $content): void
    {
        $this->commandBus->dispatch(new InitializeMessageSendingCommand($recipient, $content));
    }
}

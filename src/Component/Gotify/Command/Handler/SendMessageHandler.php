<?php

declare(strict_types=1);

namespace Component\Gotify\Command\Handler;

use Component\Gotify\Command\SendMessageCommandInterface;
use Component\Gotify\Processor\SendMessageProcessorInterface;

final readonly class SendMessageHandler implements SendMessageHandlerInterface
{
    public function __construct(
        private SendMessageProcessorInterface $sendMessageProcessor,
    ) {
    }

    public function __invoke(SendMessageCommandInterface $command): void
    {
        ($this->sendMessageProcessor)($command);
    }
}

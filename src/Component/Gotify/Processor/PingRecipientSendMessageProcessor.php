<?php

declare(strict_types=1);

namespace Component\Gotify\Processor;

use Component\Gotify\Command\SendMessageCommandInterface;
use Component\Gotify\Operator\RecipientPingerInterface;

final readonly class PingRecipientSendMessageProcessor implements SendMessageProcessorInterface
{
    public function __construct(
        private RecipientPingerInterface $recipientPinger,
    ) {
    }

    public function __invoke(SendMessageCommandInterface $command): void
    {
        $this->recipientPinger->try($command->recipient());
    }
}

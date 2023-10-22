<?php

declare(strict_types=1);

namespace Component\Gotify\Processor\Decorator;

use Component\Gotify\Command\CreateRecipientMessageSendingCommand;
use Component\Gotify\Command\ListAppsMessageSendingCommand;
use Component\Gotify\Command\SendMessageCommandInterface;
use Component\Gotify\Enum\SendMessageStep;
use Component\Gotify\Exception\RecipientDoesNotExistException;
use Component\Gotify\Processor\SendMessageProcessorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class RecipientPingingSendMessageProcessor implements SendMessageProcessorInterface
{
    public function __construct(
        private SendMessageProcessorInterface $decoratedProcessor,
        private MessageBusInterface $commandBus,
        private SendMessageStep $step,
    ) {
    }

    public function __invoke(SendMessageCommandInterface $command): void
    {
        try {
            ($this->decoratedProcessor)($command);
        } catch (RecipientDoesNotExistException $exception) {
            if (SendMessageStep::GET_RECIPIENT_FROM_LIST === $this->step) {
                $this->commandBus->dispatch(new CreateRecipientMessageSendingCommand($command->recipient(), $command->content()));

                return;
            }

            throw $exception;
        }

        $this->commandBus->dispatch(new ListAppsMessageSendingCommand($command->recipient(), $command->content()));
    }
}

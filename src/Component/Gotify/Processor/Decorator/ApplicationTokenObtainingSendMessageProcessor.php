<?php

declare(strict_types=1);

namespace Component\Gotify\Processor\Decorator;

use Component\Gotify\Command\AppTokenAwareSendMesageCommandInterface;
use Component\Gotify\Command\CreateAppTokenMessageSendingCommand;
use Component\Gotify\Command\FinalizeMessageSendingCommand;
use Component\Gotify\Command\SendMessageCommandInterface;
use Component\Gotify\Enum\SendMessageStep;
use Component\Gotify\Processor\SendMessageProcessorInterface;
use Exception;
use InvalidArgumentException;
use LogicException;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class ApplicationTokenObtainingSendMessageProcessor implements SendMessageProcessorInterface
{
    public function __construct(
        private SendMessageProcessorInterface $decoratedProcessor,
        private MessageBusInterface $commandBus,
        private SendMessageStep $step,
    ) {
    }

    public function __invoke(SendMessageCommandInterface $command): void
    {
        if (!$command instanceof AppTokenAwareSendMesageCommandInterface) {
            throw new InvalidArgumentException(sprintf('Invalid command provided. Expected "%s", got "%s".', AppTokenAwareSendMesageCommandInterface::class, $command::class));
        }

        try {
            ($this->decoratedProcessor)($command);
        } catch (Exception $exception) {
            if (SendMessageStep::GET_APPLICATION_FROM_LIST === $this->step) {
                $this->commandBus->dispatch(new CreateAppTokenMessageSendingCommand($command->recipient(), $command->content()));

                return;
            }

            throw $exception;
        }
        if (null === $command->token()) {
            throw new LogicException('Token must exist by this moment.');
        }

        $this->commandBus->dispatch(new FinalizeMessageSendingCommand($command->recipient(), $command->content(), $command->token()));
    }
}

<?php

declare(strict_types=1);

namespace Component\Gotify\Processor;

use Component\Gotify\Command\AppTokenAwareSendMesageCommandInterface;
use Component\Gotify\Command\SendMessageCommandInterface;
use Component\Gotify\Operator\MessagePublisherInterface;
use InvalidArgumentException;
use LogicException;

final readonly class PublishMessageProcessor implements SendMessageProcessorInterface
{
    public function __construct(
        private MessagePublisherInterface $messagePublisher,
    ) {
    }

    public function __invoke(SendMessageCommandInterface $command): void
    {
        if (!$command instanceof AppTokenAwareSendMesageCommandInterface) {
            throw new InvalidArgumentException(sprintf('Invalid command provided. Expected "%s", got "%s".', AppTokenAwareSendMesageCommandInterface::class, $command::class));
        }

        if (null === $command->token()) {
            throw new LogicException('Token must already exist by this moment');
        }

        $this->messagePublisher->publish($command->content(), $command->token());
    }
}

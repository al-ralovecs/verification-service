<?php

declare(strict_types=1);

namespace Component\Gotify\Processor;

use Component\Gotify\Command\AppTokenAwareSendMesageCommandInterface;
use Component\Gotify\Command\SendMessageCommandInterface;
use Component\Gotify\Exception\FailedToObtainAppTokenException;
use Component\Gotify\Operator\ApplicationTokenFetcherInterface;
use InvalidArgumentException;

final readonly class GetApplicationTokenSendMessageProcessor implements SendMessageProcessorInterface
{
    public function __construct(
        private ApplicationTokenFetcherInterface $applicationTokenFetcher,
        private string $applicationName,
    ) {
    }

    public function __invoke(SendMessageCommandInterface $command): void
    {
        if (!$command instanceof AppTokenAwareSendMesageCommandInterface) {
            throw new InvalidArgumentException(sprintf('Invalid command provided. Expected "%s", got "%s".', AppTokenAwareSendMesageCommandInterface::class, $command::class));
        }

        $token = $this->applicationTokenFetcher->try($this->applicationName, $command->recipient());
        if (null === $token) {
            throw new FailedToObtainAppTokenException($command->recipient());
        }

        $command->changeToken($token);
    }
}

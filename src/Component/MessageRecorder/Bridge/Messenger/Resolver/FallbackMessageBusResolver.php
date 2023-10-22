<?php

declare(strict_types=1);

namespace Component\MessageRecorder\Bridge\Messenger\Resolver;

use Symfony\Component\Messenger\MessageBusInterface;

final readonly class FallbackMessageBusResolver implements MessageBusResolverInterface
{
    public function __construct(
        private MessageBusInterface $bus,
    ) {
    }

    public function bus(object $message): MessageBusInterface
    {
        return $this->bus;
    }
}

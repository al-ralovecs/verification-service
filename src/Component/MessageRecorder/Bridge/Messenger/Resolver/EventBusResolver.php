<?php

declare(strict_types=1);

namespace Component\MessageRecorder\Bridge\Messenger\Resolver;

use Symfony\Component\Messenger\MessageBusInterface;

final readonly class EventBusResolver implements MessageBusResolverInterface
{
    public function __construct(
        private MessageBusInterface $bus,
    ) {
    }

    public function bus(object $message): ?MessageBusInterface
    {
        return (str_ends_with($message::class, 'Event')) ? $this->bus : null;
    }
}

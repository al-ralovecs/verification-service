<?php

declare(strict_types=1);

namespace Component\MessageRecorder\Bridge\Messenger\Resolver\Decorator;

use Component\MessageRecorder\Bridge\Messenger\Resolver\MessageBusResolverInterface;
use SN\Collection\Enum\Priority;
use SN\Collection\Model\PrioritizedCollection;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class FirstNonNullMessageBusResolver implements MessageBusResolverInterface
{
    /** @var PrioritizedCollection<MessageBusResolverInterface> */
    private PrioritizedCollection $collection;

    public function __construct()
    {
        $this->collection = new PrioritizedCollection();
    }

    public function add(MessageBusResolverInterface $resolver, int $priority = Priority::NORMAL): void
    {
        $this->collection->add($resolver, $priority);
    }

    public function bus(object $message): ?MessageBusInterface
    {
        /** @var MessageBusInterface|null $messageBus */
        $messageBus = $this->collection->firstNonNullResult(
            static fn (MessageBusResolverInterface $resolver): ?MessageBusInterface => $resolver->bus($message),
        );

        return $messageBus;
    }
}

<?php

declare(strict_types=1);

namespace Component\Messenger\Handler\Decorator;

use Closure;
use Component\Dao\Exception\LockWaitTimeoutException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

final readonly class LockWaitTimeoutExceededRetryableMessageHandler
{
    private Closure $decoratedHandler;

    public function __construct(
        callable $decoratedHandler,
        private MessageBusInterface $messageBus,
    ) {
        $this->decoratedHandler = $decoratedHandler(...);
    }

    public function __invoke(object $message): void
    {
        try {
            ($this->decoratedHandler)($message);
        } catch (LockWaitTimeoutException) {
            $this->messageBus->dispatch($message, [
                new DelayStamp(random_int(10, 60) * 1000),
                new DispatchAfterCurrentBusStamp(),
            ]);
        }
    }
}

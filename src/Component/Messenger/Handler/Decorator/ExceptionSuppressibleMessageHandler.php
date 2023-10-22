<?php

declare(strict_types=1);

namespace Component\Messenger\Handler\Decorator;

use Closure;
use Component\Messenger\Exception\SuppressibleMessageHandlingExceptionInterface;

final readonly class ExceptionSuppressibleMessageHandler
{
    private Closure $decoratedHandler;

    public function __construct(
        callable $decoratedHandler,
    ) {
        $this->decoratedHandler = $decoratedHandler(...);
    }

    public function __invoke(object $message): void
    {
        try {
            ($this->decoratedHandler)($message);
        } catch (SuppressibleMessageHandlingExceptionInterface $e) {
        }
    }
}

<?php

declare(strict_types=1);

namespace Component\Messenger\Bridge\Doctrine\Messenger\Middleware;

use Component\Messenger\Bridge\Doctrine\DBAL\ConnectionStack;
use Component\Messenger\Model\MessageToConnectionMapInterface;
use Doctrine\Persistence\ConnectionRegistry;
use InvalidArgumentException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

final readonly class TransactionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ConnectionRegistry $connectionRegistry,
        private readonly MessageToConnectionMapInterface $messageToConnectionMap,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null === $connectionStack = $this->connections($envelope)) {
            return $stack->next()->handle($envelope, $stack);
        }

        try {
            /* @phpstan-ignore-next-line */
            return $connectionStack->transactional(
                function () use ($envelope, $stack): Envelope {
                    return $stack->next()->handle($envelope, $stack);
                },
            );
        } catch (Throwable $exception) {
            if ($exception instanceof HandlerFailedException) {
                // Remove all HandledStamp from the envelope so the retry will execute all handlers again.
                // When a handler fails, the queries of allegedly successful previous handlers just got rolled back.
                throw new HandlerFailedException($exception->getEnvelope()->withoutAll(HandledStamp::class), $exception->getNestedExceptions());
            }

            throw $exception;
        }
    }

    private function connections(Envelope $envelope): ?ConnectionStack
    {
        if (0 === count($this->messageToConnectionMap->connections($envelope->getMessage()::class))) {
            return null;
        }

        $connections = [...$this->messageToConnectionMap->connections($envelope->getMessage()::class)];

        try {
            return array_reduce(
                array_unique($connections),
                function (?ConnectionStack $connectionStack, string $connection): ConnectionStack {
                    $connectionStack = $connectionStack ?? new ConnectionStack();
                    $connectionStack->add($this->connectionRegistry->getConnection($connection));

                    return $connectionStack;
                },
                null,
            );
        } catch (InvalidArgumentException $e) {
            throw new UnrecoverableMessageHandlingException($e->getMessage(), 0, $e);
        }
    }
}

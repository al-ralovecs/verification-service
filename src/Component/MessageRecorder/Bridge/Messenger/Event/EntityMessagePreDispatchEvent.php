<?php

declare(strict_types=1);

namespace Component\MessageRecorder\Bridge\Messenger\Event;

use Symfony\Component\Messenger\Envelope;

/**
 * @template T of object
 */
final class EntityMessagePreDispatchEvent
{
    /**
     * @param T $entity
     */
    public function __construct(
        private readonly object $entity,
        private Envelope $envelope,
    ) {
    }

    /**
     * @return T
     */
    public function entity(): object
    {
        return $this->entity;
    }

    public function envelope(): Envelope
    {
        return $this->envelope;
    }

    public function withEnvelope(Envelope $envelope): void
    {
        $this->envelope = $envelope;
    }
}

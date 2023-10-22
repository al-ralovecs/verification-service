<?php

declare(strict_types=1);

namespace Component\Notification\Model;

use Component\MessageRecorder\Model\ContainsRecordedMessages;
use Component\MessageRecorder\Model\PrivateMessageRecorderCapabilities;
use Component\Notification\Command\CreateNotificationCommand;
use Component\Notification\Command\DispatchNotificationCommand;
use Component\Notification\Enum\Channel;
use Component\Notification\Event\NotificationCreatedEvent;
use Component\Notification\Event\NotificationDispatchedEvent;
use Component\Notification\Exception\CannotDispatchNotificationException;
use DateTimeImmutable;

class Notification implements NotificationInterface, ContainsRecordedMessages
{
    use PrivateMessageRecorderCapabilities;

    private bool $dispatched;
    private DateTimeImmutable $updatedAt;

    public function __construct(
        private readonly string $id,
        private readonly string $recipient,
        private readonly Channel $channel,
        private readonly NotificationBodyInterface $body,
        private readonly DateTimeImmutable $createdAt,
    ) {
        $this->dispatched = false;
        $this->updatedAt = $this->createdAt;

        $this->record(new DispatchNotificationCommand($this->id));
        $this->record(new NotificationCreatedEvent($this->id, $this->createdAt));
    }

    public static function create(CreateNotificationCommand $command): self
    {
        return new self(
            $command->id(),
            $command->recipient(),
            $command->channel(),
            $command->body(),
            $command->createdAt(),
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function recipient(): string
    {
        return $this->recipient;
    }

    public function channel(): Channel
    {
        return $this->channel;
    }

    public function body(): NotificationBodyInterface
    {
        return $this->body;
    }

    public function dispatched(): bool
    {
        return $this->dispatched;
    }

    public function dispatch(): void
    {
        if (true === $this->dispatched) {
            throw CannotDispatchNotificationException::alreadyDispatched($this->id);
        }

        $this->dispatched = true;
        $this->updatedAt = new DateTimeImmutable();

        $this->record(new NotificationDispatchedEvent($this->id, $this->updatedAt));
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}

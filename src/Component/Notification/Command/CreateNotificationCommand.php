<?php

declare(strict_types=1);

namespace Component\Notification\Command;

use Component\Notification\Enum\Channel;
use Component\Notification\Model\NotificationBody;
use Component\Notification\Model\NotificationBodyInterface;
use Component\Notification\Transformer\VerificationSubjectTypeToChannelTransformer;
use Component\Verification\Event\VerificationCreatedEvent;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final readonly class CreateNotificationCommand
{
    public function __construct(
        private string $id,
        private string $recipient,
        private Channel $channel,
        private NotificationBodyInterface $body,
        private DateTimeImmutable $createdAt,
    ) {
    }

    public static function fromEvent(VerificationCreatedEvent $event): self
    {
        return new self(
            (string) Uuid::v7(),
            $event->subject()->identity(),
            VerificationSubjectTypeToChannelTransformer::transform($event->subject()->type()),
            new NotificationBody(
                $event->code(),
            ),
            new DateTimeImmutable(),
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

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}

<?php

declare(strict_types=1);

namespace Component\Notification\Event;

use DateTimeImmutable;
use JsonSerializable;

abstract readonly class AbstractNotificationEvent implements JsonSerializable
{
    public function __construct(
        private string $id,
        private DateTimeImmutable $occurredOn,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id(),
            'occurred_on' => $this->occurredOn()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function __serialize(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * @param array<string, string> $data
     */
    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->occurredOn = new DateTimeImmutable($data['occurred_on']);
    }
}

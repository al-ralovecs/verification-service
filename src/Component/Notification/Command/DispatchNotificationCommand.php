<?php

declare(strict_types=1);

namespace Component\Notification\Command;

use JsonSerializable;

final readonly class DispatchNotificationCommand implements JsonSerializable
{
    public function __construct(
        private string $id,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
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
    }
}

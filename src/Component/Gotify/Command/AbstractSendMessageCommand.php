<?php

declare(strict_types=1);

namespace Component\Gotify\Command;

use JsonSerializable;

abstract class AbstractSendMessageCommand implements SendMessageCommandInterface, JsonSerializable
{
    public function __construct(
        private readonly string $recipient,
        private readonly string $content,
    ) {
    }

    public function recipient(): string
    {
        return $this->recipient;
    }

    public function content(): string
    {
        return $this->content;
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
            'recipient' => $this->recipient,
            'content' => $this->content,
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
        $this->recipient = $data['recipient'];
        $this->content = $data['content'];
    }
}

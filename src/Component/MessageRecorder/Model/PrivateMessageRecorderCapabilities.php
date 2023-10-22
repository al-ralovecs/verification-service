<?php

declare(strict_types=1);

namespace Component\MessageRecorder\Model;

trait PrivateMessageRecorderCapabilities
{
    /** @var array<object> */
    private array $messages = [];

    public function recordedMessages(): iterable
    {
        return $this->messages;
    }

    public function eraseMessages(): void
    {
        $this->messages = [];
    }

    protected function record(object $message): void
    {
        $this->messages[] = $message;
    }
}

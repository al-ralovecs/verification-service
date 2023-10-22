<?php

declare(strict_types=1);

namespace Component\Notification\Model;

final class NotificationBody implements NotificationBodyInterface
{
    private ?string $content = null;

    public function __construct(
        private readonly string $code,
    ) {
    }

    public function code(): string
    {
        return $this->code;
    }

    public function content(): ?string
    {
        return $this->content;
    }

    public function changeContent(string $content): void
    {
        $this->content = $content;
    }
}

<?php

declare(strict_types=1);

namespace Component\Notification\Model;

interface NotificationBodyInterface
{
    public function code(): string;

    public function content(): ?string;

    public function changeContent(string $content): void;
}

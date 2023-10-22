<?php

declare(strict_types=1);

namespace Component\Notification\Model;

use Component\Notification\Enum\Channel;
use DateTimeImmutable;

interface NotificationInterface
{
    public function id(): string;

    public function recipient(): string;

    public function channel(): Channel;

    public function body(): NotificationBodyInterface;

    public function dispatched(): bool;

    public function dispatch(): void;

    public function createdAt(): DateTimeImmutable;

    public function updatedAt(): DateTimeImmutable;
}

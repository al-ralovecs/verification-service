<?php

declare(strict_types=1);

namespace Component\Notification\Operator;

use Component\Notification\Model\NotificationInterface;

interface NotificationFetcherInterface
{
    public function getById(string $notificationId, bool $lock = false): NotificationInterface;
}

<?php

declare(strict_types=1);

namespace Component\Notification\Operator;

use Component\Notification\Model\NotificationInterface;

interface NotificationPersisterInterface
{
    public function save(NotificationInterface ...$notifications): void;
}

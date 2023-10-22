<?php

declare(strict_types=1);

namespace Component\Notification\Processor;

use Component\Notification\Model\NotificationInterface;

interface NotificationProcessorInterface
{
    public function __invoke(NotificationInterface $notification): void;
}

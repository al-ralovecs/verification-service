<?php

declare(strict_types=1);

namespace Component\Notification\Processor;

use Component\Notification\Enum\Channel;

interface NotificationProcessorMapInterface
{
    public function dispatcher(Channel $channel): NotificationProcessorInterface;
}

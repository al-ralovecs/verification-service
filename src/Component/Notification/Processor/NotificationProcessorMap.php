<?php

declare(strict_types=1);

namespace Component\Notification\Processor;

use Component\Notification\Enum\Channel;

final class NotificationProcessorMap implements NotificationProcessorMapInterface
{
    /** @var array<string, NotificationProcessorInterface> */
    private array $processors = [];

    public function add(Channel $channel, NotificationProcessorInterface $notificationProcessor): void
    {
        $this->processors[$channel->value] = $notificationProcessor;
    }

    public function dispatcher(Channel $channel): NotificationProcessorInterface
    {
        return $this->processors[$channel->value];
    }
}

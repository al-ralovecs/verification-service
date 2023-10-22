<?php

declare(strict_types=1);

namespace Component\Notification\Processor;

use Component\Notification\Fetcher\NotificationContentFetcherInterface;
use Component\Notification\Model\NotificationInterface;

final readonly class NotificationContentFetchingProcessor implements NotificationProcessorInterface
{
    public function __construct(
        private NotificationContentFetcherInterface $notificationContentFetcher,
    ) {
    }

    public function __invoke(NotificationInterface $notification): void
    {
        $body = $this->notificationContentFetcher->get(
            $notification->channel(),
            $notification->body()->code(),
        );

        $notification->body()->changeContent($body);
    }
}

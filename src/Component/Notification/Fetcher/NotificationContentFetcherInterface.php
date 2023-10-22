<?php

declare(strict_types=1);

namespace Component\Notification\Fetcher;

use Component\Notification\Enum\Channel;

interface NotificationContentFetcherInterface
{
    public function get(Channel $channel, string $code): string;
}

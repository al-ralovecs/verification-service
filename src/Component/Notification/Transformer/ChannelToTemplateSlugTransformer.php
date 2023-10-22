<?php

declare(strict_types=1);

namespace Component\Notification\Transformer;

use Component\Notification\Enum\Channel;

final readonly class ChannelToTemplateSlugTransformer
{
    public static function transform(Channel $channel): string
    {
        return match (true) {
            Channel::MOBILE === $channel => 'mobile-verification',
            Channel::EMAIL === $channel => 'email-verification',
        };
    }
}

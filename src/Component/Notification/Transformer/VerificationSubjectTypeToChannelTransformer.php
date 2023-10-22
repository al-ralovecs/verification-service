<?php

declare(strict_types=1);

namespace Component\Notification\Transformer;

use Component\Notification\Enum\Channel;
use Component\Verification\Operator\Enum\VerificationSubjectType;

final readonly class VerificationSubjectTypeToChannelTransformer
{
    public static function transform(VerificationSubjectType $type): Channel
    {
        return match (true) {
            VerificationSubjectType::MOBILE === $type => Channel::MOBILE,
            VerificationSubjectType::EMAIL === $type => Channel::EMAIL,
        };
    }
}

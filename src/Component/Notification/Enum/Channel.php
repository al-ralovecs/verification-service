<?php

declare(strict_types=1);

namespace Component\Notification\Enum;

enum Channel: string
{
    case EMAIL = 'email';
    case MOBILE = 'mobile';
}

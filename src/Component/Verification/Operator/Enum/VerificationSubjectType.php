<?php

declare(strict_types=1);

namespace Component\Verification\Operator\Enum;

enum VerificationSubjectType: string
{
    case EMAIL = 'email_confirmation';
    case MOBILE = 'mobile_confirmation';
}

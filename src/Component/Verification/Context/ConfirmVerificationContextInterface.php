<?php

declare(strict_types=1);

namespace Component\Verification\Context;

use Component\Verification\Model\VerificationUserInfoInterface;

interface ConfirmVerificationContextInterface
{
    public function code(): string;

    public function userInfo(): VerificationUserInfoInterface;
}

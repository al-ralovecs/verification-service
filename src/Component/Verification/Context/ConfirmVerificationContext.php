<?php

declare(strict_types=1);

namespace Component\Verification\Context;

use Component\Verification\Model\VerificationUserInfoInterface;

final readonly class ConfirmVerificationContext implements ConfirmVerificationContextInterface
{
    public function __construct(
        private string $code,
        private VerificationUserInfoInterface $userInfo,
    ) {
    }

    public function code(): string
    {
        return $this->code;
    }

    public function userInfo(): VerificationUserInfoInterface
    {
        return $this->userInfo;
    }
}

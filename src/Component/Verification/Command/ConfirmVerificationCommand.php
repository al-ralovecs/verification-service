<?php

declare(strict_types=1);

namespace Component\Verification\Command;

use Component\Verification\Model\VerificationUserInfoInterface;

final readonly class ConfirmVerificationCommand
{
    public function __construct(
        private string $id,
        private VerificationUserInfoInterface $userInfo,
        private string $code,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function userInfo(): VerificationUserInfoInterface
    {
        return $this->userInfo;
    }

    public function code(): string
    {
        return $this->code;
    }
}

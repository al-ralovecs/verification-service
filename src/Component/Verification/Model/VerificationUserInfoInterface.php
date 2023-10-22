<?php

declare(strict_types=1);

namespace Component\Verification\Model;

interface VerificationUserInfoInterface
{
    public function ipAddress(): string;

    public function userAgent(): string;

    public function equals(VerificationUserInfoInterface $userInfo): bool;
}

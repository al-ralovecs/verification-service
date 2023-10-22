<?php

declare(strict_types=1);

namespace Component\Verification\Model;

final readonly class VerificationUserInfo implements VerificationUserInfoInterface
{
    public function __construct(
        private string $ipAddress,
        private string $userAgent,
    ) {
    }

    public function ipAddress(): string
    {
        return $this->ipAddress;
    }

    public function userAgent(): string
    {
        return $this->userAgent;
    }

    public function equals(VerificationUserInfoInterface $userInfo): bool
    {
        return $this->ipAddress() === $userInfo->ipAddress() && $this->userAgent() === $userInfo->userAgent();
    }
}

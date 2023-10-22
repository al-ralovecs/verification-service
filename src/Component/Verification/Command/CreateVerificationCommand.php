<?php

declare(strict_types=1);

namespace Component\Verification\Command;

use Component\Verification\Model\VerificationUserInfoInterface;
use Component\Verification\Operator\VerificationSubjectInterface;
use DateTimeImmutable;

final readonly class CreateVerificationCommand
{
    public function __construct(
        private string $id,
        private VerificationSubjectInterface $subject,
        private VerificationUserInfoInterface $userInfo,
        private DateTimeImmutable $createdAt,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function subject(): VerificationSubjectInterface
    {
        return $this->subject;
    }

    public function userInfo(): VerificationUserInfoInterface
    {
        return $this->userInfo;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}

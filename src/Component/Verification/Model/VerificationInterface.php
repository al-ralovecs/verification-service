<?php

declare(strict_types=1);

namespace Component\Verification\Model;

use Component\Verification\Operator\VerificationSubjectInterface;
use DateTimeImmutable;

interface VerificationInterface
{
    public function id(): string;

    public function subject(): VerificationSubjectInterface;

    public function confirmed(): bool;

    public function confirm(): void;

    /** @return numeric-string */
    public function code(): string;

    public function userInfo(): VerificationUserInfoInterface;

    public function attempts(): int;

    public function failed(string $code): void;

    public function createdAt(): DateTimeImmutable;

    public function updatedAt(): DateTimeImmutable;
}

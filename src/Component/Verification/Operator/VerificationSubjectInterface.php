<?php

declare(strict_types=1);

namespace Component\Verification\Operator;

use Component\Verification\Operator\Enum\VerificationSubjectType;
use JsonSerializable;

interface VerificationSubjectInterface extends JsonSerializable
{
    public function identity(): string;

    public function type(): VerificationSubjectType;

    public function equals(VerificationSubjectInterface $subject): bool;

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array;
}

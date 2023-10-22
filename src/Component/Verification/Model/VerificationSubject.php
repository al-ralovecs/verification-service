<?php

declare(strict_types=1);

namespace Component\Verification\Model;

use Component\Verification\Operator\Enum\VerificationSubjectType;
use Component\Verification\Operator\VerificationSubjectInterface;

final readonly class VerificationSubject implements VerificationSubjectInterface
{
    public function __construct(
        private string $identity,
        private VerificationSubjectType $type,
    ) {
    }

    public function identity(): string
    {
        return $this->identity;
    }

    public function type(): VerificationSubjectType
    {
        return $this->type;
    }

    public function equals(VerificationSubjectInterface $subject): bool
    {
        return $this->identity() === $subject->identity() && $this->type() === $subject->type();
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type->value,
            'identity' => $this->identity,
        ];
    }
}

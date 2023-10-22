<?php

declare(strict_types=1);

namespace Component\Verification\Bridge\Api\Request;

use Component\Verification\Bridge\Api\Exception\VerificationRequestValidationFailedException;
use Component\Verification\Model\VerificationSubject;
use Component\Verification\Operator\Enum\VerificationSubjectType;
use Component\Verification\Operator\VerificationSubjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateVerificationRequest
{
    private const SUBJECT_IDENTITY = 'identity';
    private const SUBJECT_TYPE = 'type';

    /**
     * @param array<string, string|int> $subject
     */
    public function __construct(
        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('array'),
        ])]
        public array $subject,
    ) {
        $this->validate();
    }

    public function subject(): VerificationSubjectInterface
    {
        return new VerificationSubject(
            (string) $this->subject[self::SUBJECT_IDENTITY],
            VerificationSubjectType::from($this->subject[self::SUBJECT_TYPE]),
        );
    }

    private function validate(): void
    {
        if (!array_key_exists(self::SUBJECT_IDENTITY, $this->subject)) {
            throw VerificationRequestValidationFailedException::missingSubjectParam(self::SUBJECT_IDENTITY);
        }

        if (!array_key_exists(self::SUBJECT_TYPE, $this->subject)) {
            throw VerificationRequestValidationFailedException::missingSubjectParam(self::SUBJECT_TYPE);
        }

        if (null === VerificationSubjectType::tryFrom($this->subject[self::SUBJECT_TYPE])) {
            throw VerificationRequestValidationFailedException::invalidSubjectType((string) $this->subject[self::SUBJECT_TYPE]);
        }
    }
}

<?php

declare(strict_types=1);

namespace Component\Verification\Validator\Create;

use Component\Verification\Checker\DuplicateVerificationCheckerInterface;
use Component\Verification\Exception\VerificationAlreadyExistsException;
use Component\Verification\Model\VerificationInterface;

final readonly class CreateVerificationDuplicateValidator implements CreateVerificationValidatorInterface
{
    public function __construct(
        private DuplicateVerificationCheckerInterface $duplicateVerificationChecker,
    ) {
    }

    public function __invoke(VerificationInterface $verification): void
    {
        if (!$this->duplicateVerificationChecker->duplicateExists($verification->subject())) {
            return;
        }

        throw new VerificationAlreadyExistsException($verification->subject());
    }
}

<?php

declare(strict_types=1);

namespace Component\Verification\Validator\Confirm;

use Component\Verification\Context\ConfirmVerificationContextInterface;
use Component\Verification\Exception\VerificationExpiredException;
use Component\Verification\Model\VerificationInterface;

final readonly class ConfirmVerificationAttemptsValidator implements ConfirmVerificationValidatorInterface
{
    public function __construct(
        private int $maxAttempts,
    ) {
    }

    public function __invoke(VerificationInterface $verification, ConfirmVerificationContextInterface $context): void
    {
        if ($verification->attempts() < $this->maxAttempts) {
            return;
        }

        throw new VerificationExpiredException($verification->id());
    }
}

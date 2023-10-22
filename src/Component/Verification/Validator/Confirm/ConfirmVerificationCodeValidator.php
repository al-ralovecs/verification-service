<?php

declare(strict_types=1);

namespace Component\Verification\Validator\Confirm;

use Component\Verification\Context\ConfirmVerificationContextInterface;
use Component\Verification\Exception\VerificationFailedException;
use Component\Verification\Model\VerificationInterface;

final readonly class ConfirmVerificationCodeValidator implements ConfirmVerificationValidatorInterface
{
    public function __invoke(VerificationInterface $verification, ConfirmVerificationContextInterface $context): void
    {
        if ($verification->code() === $context->code()) {
            return;
        }

        throw new VerificationFailedException($verification->id());
    }
}

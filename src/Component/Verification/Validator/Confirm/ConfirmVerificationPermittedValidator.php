<?php

declare(strict_types=1);

namespace Component\Verification\Validator\Confirm;

use Component\Verification\Context\ConfirmVerificationContextInterface;
use Component\Verification\Exception\VerificationNotPermittedException;
use Component\Verification\Model\VerificationInterface;

final readonly class ConfirmVerificationPermittedValidator implements ConfirmVerificationValidatorInterface
{
    public function __invoke(VerificationInterface $verification, ConfirmVerificationContextInterface $context): void
    {
        if ($verification->userInfo()->equals($context->userInfo())) {
            return;
        }

        throw new VerificationNotPermittedException($verification->id());
    }
}

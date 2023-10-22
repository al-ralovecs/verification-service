<?php

declare(strict_types=1);

namespace Component\Verification\Validator\Confirm;

use Component\Verification\Context\ConfirmVerificationContextInterface;
use Component\Verification\Model\VerificationInterface;

interface ConfirmVerificationValidatorInterface
{
    public function __invoke(VerificationInterface $verification, ConfirmVerificationContextInterface $context): void;
}

<?php

declare(strict_types=1);

namespace Component\Verification\Processor\Confirm;

use Component\Verification\Context\ConfirmVerificationContextInterface;
use Component\Verification\Model\VerificationInterface;

final readonly class ConfirmingVerificationProcessor implements VerificationProcessorInterface
{
    public function __invoke(VerificationInterface $verification, ConfirmVerificationContextInterface $context): void
    {
        $verification->confirm();
    }
}

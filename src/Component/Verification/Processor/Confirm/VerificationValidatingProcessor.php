<?php

declare(strict_types=1);

namespace Component\Verification\Processor\Confirm;

use Component\Verification\Context\ConfirmVerificationContextInterface;
use Component\Verification\Model\VerificationInterface;
use Component\Verification\Validator\Confirm\ConfirmVerificationValidatorInterface;

final readonly class VerificationValidatingProcessor implements VerificationProcessorInterface
{
    public function __construct(
        private ConfirmVerificationValidatorInterface $confirmVerificationValidator,
    ) {
    }

    public function __invoke(VerificationInterface $verification, ConfirmVerificationContextInterface $context): void
    {
        ($this->confirmVerificationValidator)($verification, $context);
    }
}

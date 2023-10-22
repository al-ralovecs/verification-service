<?php

declare(strict_types=1);

namespace Component\Verification\Processor\Create;

use Component\Verification\Model\VerificationInterface;
use Component\Verification\Validator\Create\CreateVerificationValidatorInterface;

final readonly class VerificationValidatingProcessor implements VerificationProcessorInterface
{
    public function __construct(
        private CreateVerificationValidatorInterface $verificationValidator,
    ) {
    }

    public function __invoke(VerificationInterface $verification): void
    {
        ($this->verificationValidator)($verification);
    }
}

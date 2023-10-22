<?php

declare(strict_types=1);

namespace Component\Verification\Validator\Create;

use Component\Verification\Model\VerificationInterface;

interface CreateVerificationValidatorInterface
{
    public function __invoke(VerificationInterface $verification): void;
}

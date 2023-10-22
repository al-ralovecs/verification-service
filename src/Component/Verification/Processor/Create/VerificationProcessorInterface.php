<?php

declare(strict_types=1);

namespace Component\Verification\Processor\Create;

use Component\Verification\Model\VerificationInterface;

interface VerificationProcessorInterface
{
    public function __invoke(VerificationInterface $verification): void;
}

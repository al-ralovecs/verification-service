<?php

declare(strict_types=1);

namespace Component\Verification\Operator;

use Component\Verification\Model\VerificationInterface;

interface VerificationPersisterInterface
{
    public function save(VerificationInterface ...$verifications): void;
}

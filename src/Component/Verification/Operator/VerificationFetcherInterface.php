<?php

declare(strict_types=1);

namespace Component\Verification\Operator;

use Component\Verification\Exception\VerificationNotFoundException;
use Component\Verification\Model\VerificationInterface;

interface VerificationFetcherInterface
{
    /**
     * @throws VerificationNotFoundException
     */
    public function getById(string $id): VerificationInterface;
}

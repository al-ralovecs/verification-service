<?php

declare(strict_types=1);

namespace Component\Verification\Checker;

use Component\Verification\Operator\VerificationSubjectInterface;

interface DuplicateVerificationCheckerInterface
{
    public function duplicateExists(VerificationSubjectInterface $subject): bool;
}

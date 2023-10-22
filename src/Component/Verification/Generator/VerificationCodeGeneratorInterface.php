<?php

declare(strict_types=1);

namespace Component\Verification\Generator;

interface VerificationCodeGeneratorInterface
{
    /**
     * @return numeric-string
     */
    public function code(): string;
}

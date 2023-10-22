<?php

declare(strict_types=1);

namespace Component\Verification\Exception;

use Component\Common\Operator\StatusCodeAwareExceptionInterface;
use Component\Verification\Operator\VerificationSubjectInterface;
use RuntimeException;

final class VerificationAlreadyExistsException extends RuntimeException implements StatusCodeAwareExceptionInterface
{
    private const CODE = 409;
    private const MESSAGE = 'Verification for identity "%s" already exists';

    public function __construct(VerificationSubjectInterface $subject)
    {
        parent::__construct(sprintf(self::MESSAGE, $subject->identity()));
    }

    public function statusCode(): int
    {
        return self::CODE;
    }
}

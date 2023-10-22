<?php

declare(strict_types=1);

namespace Component\Verification\Exception;

use Component\Common\Operator\StatusCodeAwareExceptionInterface;
use RuntimeException;

final class VerificationFailedException extends RuntimeException implements StatusCodeAwareExceptionInterface
{
    private const CODE = 422;
    private const MESSAGE = 'Validation "%s" failed: invalid code supplied';

    public function __construct(string $verificationId)
    {
        parent::__construct(sprintf(self::MESSAGE, $verificationId));
    }

    public function statusCode(): int
    {
        return self::CODE;
    }
}

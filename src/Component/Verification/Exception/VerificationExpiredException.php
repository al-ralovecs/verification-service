<?php

declare(strict_types=1);

namespace Component\Verification\Exception;

use Component\Common\Operator\StatusCodeAwareExceptionInterface;
use RuntimeException;

final class VerificationExpiredException extends RuntimeException implements StatusCodeAwareExceptionInterface
{
    private const CODE = 410;
    private const MESSAGE = 'Verification "%s" expired';

    public function __construct(string $verificationId)
    {
        parent::__construct(sprintf(self::MESSAGE, $verificationId));
    }

    public function statusCode(): int
    {
        return self::CODE;
    }
}

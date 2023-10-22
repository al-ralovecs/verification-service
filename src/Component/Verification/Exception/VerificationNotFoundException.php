<?php

declare(strict_types=1);

namespace Component\Verification\Exception;

use Component\Common\Operator\StatusCodeAwareExceptionInterface;
use RuntimeException;

final class VerificationNotFoundException extends RuntimeException implements StatusCodeAwareExceptionInterface
{
    private const CODE = 404;
    private const MESSAGE = 'Verification "%s" not found';

    public function __construct(string $verificationId)
    {
        parent::__construct(sprintf(self::MESSAGE, $verificationId));
    }

    public function statusCode(): int
    {
        return self::CODE;
    }
}

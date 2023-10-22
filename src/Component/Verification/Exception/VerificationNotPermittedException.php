<?php

declare(strict_types=1);

namespace Component\Verification\Exception;

use Component\Common\Operator\StatusCodeAwareExceptionInterface;
use RuntimeException;

final class VerificationNotPermittedException extends RuntimeException implements StatusCodeAwareExceptionInterface
{
    private const CODE = 403;
    private const MESSAGE = 'No permission to confirm verification "%s"';

    public function __construct(string $verificationId)
    {
        parent::__construct(sprintf(self::MESSAGE, $verificationId));
    }

    public function statusCode(): int
    {
        return self::CODE;
    }
}

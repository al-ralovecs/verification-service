<?php

declare(strict_types=1);

namespace Component\Verification\Exception;

use Component\Common\Operator\StatusCodeAwareExceptionInterface;
use Component\Verification\Model\VerificationInterface;
use RuntimeException;

final class CannotConfirmVerificationException extends RuntimeException implements StatusCodeAwareExceptionInterface
{
    private const CODE = 410;

    public static function alreadyConfirmed(VerificationInterface $verification): self
    {
        return new self(sprintf('Verification "%s" is already confirmed', $verification->id()));
    }

    public function statusCode(): int
    {
        return self::CODE;
    }
}

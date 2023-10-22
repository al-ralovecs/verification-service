<?php

declare(strict_types=1);

namespace Component\Verification\Bridge\Api\Exception;

use Component\Common\Operator\StatusCodeAwareExceptionInterface;
use InvalidArgumentException;

final class VerificationRequestValidationFailedException extends InvalidArgumentException implements StatusCodeAwareExceptionInterface
{
    private const CODE = 422;

    public static function missingSubjectParam(string $param): self
    {
        return new self(sprintf('Subject parameter "%s" is missing', $param));
    }

    public static function invalidSubjectType(string $type): self
    {
        return new self(sprintf('Invalid subject type "%s" provided', $type));
    }

    public static function missingHeader(string $header): self
    {
        return new self(sprintf('Header "%s" is missing', $header));
    }

    public static function unrecognizedIpAddress(): self
    {
        return new self('Failed to recognize your ip address');
    }

    public function statusCode(): int
    {
        return self::CODE;
    }
}

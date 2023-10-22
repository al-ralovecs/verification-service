<?php

declare(strict_types=1);

namespace Component\Template\Exception;

use Component\Common\Operator\StatusCodeAwareExceptionInterface;
use InvalidArgumentException;

final class RenderTemplateValidationFailedException extends InvalidArgumentException implements StatusCodeAwareExceptionInterface
{
    public const CODE = 422;

    public static function missingVariable(string $variable): self
    {
        return new self(sprintf('Required variable "%s" is missing', $variable));
    }

    public static function missingValue(string $variable): self
    {
        return new self(sprintf('Variable "%s" value missing', $variable));
    }

    public function statusCode(): int
    {
        return self::CODE;
    }
}

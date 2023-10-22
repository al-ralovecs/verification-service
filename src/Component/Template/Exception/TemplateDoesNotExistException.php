<?php

declare(strict_types=1);

namespace Component\Template\Exception;

use Component\Common\Operator\StatusCodeAwareExceptionInterface;
use RuntimeException;

final class TemplateDoesNotExistException extends RuntimeException implements StatusCodeAwareExceptionInterface
{
    private const MESSAGE = 'Template with slug "%s" not found';
    private const CODE = 404;

    public static function missingSlug(string $slug): self
    {
        return new self(sprintf(self::MESSAGE, $slug));
    }

    public function statusCode(): int
    {
        return self::CODE;
    }
}

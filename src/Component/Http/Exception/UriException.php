<?php

declare(strict_types=1);

namespace Component\Http\Exception;

use RuntimeException;

final class UriException extends RuntimeException
{
    private const MESSAGE = 'Could not set "%s" parameter of "%s" path.';

    public static function missingPathParameter(string $path, string $param): self
    {
        return new self(sprintf(self::MESSAGE, $param, $path));
    }
}

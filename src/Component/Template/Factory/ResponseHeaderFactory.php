<?php

declare(strict_types=1);

namespace Component\Template\Factory;

use Component\Template\Enum\ContentType;

final readonly class ResponseHeaderFactory
{
    private const HEADER_CONTENT_TYPE_KEY = 'Content-Type';
    private const HEADER_CONTENT_TYPE_VALUE_TEMPLATE = '%s; charset=UTF-8';

    /**
     * @return array<string, string>
     */
    public static function contentTypeHeader(ContentType $contentType): array
    {
        return [
            self::HEADER_CONTENT_TYPE_KEY => sprintf(self::HEADER_CONTENT_TYPE_VALUE_TEMPLATE, $contentType->value),
        ];
    }
}

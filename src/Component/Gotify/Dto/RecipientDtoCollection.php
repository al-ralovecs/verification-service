<?php

declare(strict_types=1);

namespace Component\Gotify\Dto;

use SN\Collection\Model\ArrayObject;

/**
 * @method bool has(string|int $key)
 */
final class RecipientDtoCollection extends ArrayObject
{
    /**
     * @param array<string, RecipientDto> $data
     */
    public static function create(array $data): self
    {
        return new RecipientDtoCollection($data);
    }
}

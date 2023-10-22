<?php

declare(strict_types=1);

namespace Component\Gotify\Dto;

use SN\Collection\Model\ArrayObject;

final class ApplicationDtoCollection extends ArrayObject
{
    /**
     * @param array<string, ApplicationDto> $data
     */
    public static function create(array $data): self
    {
        return new ApplicationDtoCollection($data);
    }
}

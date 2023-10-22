<?php

declare(strict_types=1);

namespace Component\Gotify\Dto;

final readonly class MessageDto
{
    /**
     * @param array<string, int|string> $data
     */
    public function __construct(
        private array $data,
    ) {
    }

    public function message(): string
    {
        assert(array_key_exists('message', $this->data));

        return (string) $this->data['message'];
    }
}

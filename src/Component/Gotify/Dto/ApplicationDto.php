<?php

declare(strict_types=1);

namespace Component\Gotify\Dto;

final readonly class ApplicationDto
{
    /**
     * @param array<string, int|string> $data
     */
    public function __construct(
        private array $data,
    ) {
    }

    public function name(): string
    {
        assert(array_key_exists('name', $this->data));

        return (string) $this->data['name'];
    }

    public function token(): string
    {
        assert(array_key_exists('token', $this->data));

        return (string) $this->data['token'];
    }
}

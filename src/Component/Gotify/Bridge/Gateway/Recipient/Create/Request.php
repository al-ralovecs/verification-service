<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Recipient\Create;

use Component\Gateway\Operator\RequestInterface;

final readonly class Request implements RequestInterface
{
    public function __construct(
        private string $name,
        private string $pass,
        private bool $admin = false,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function pass(): string
    {
        return $this->pass;
    }

    public function admin(): bool
    {
        return $this->admin;
    }
}

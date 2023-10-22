<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Application\Create;

use Component\Gateway\Operator\RequestInterface;

final readonly class Request implements RequestInterface
{
    public function __construct(
        private string $name,
        private string $description,
        private string $recipient,
        private string $password,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function recipient(): string
    {
        return $this->recipient;
    }

    public function password(): string
    {
        return $this->password;
    }
}

<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Application\List;

use Component\Gateway\Operator\RequestInterface;

final readonly class Request implements RequestInterface
{
    public function __construct(
        private string $recipient,
        private string $password,
    ) {
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

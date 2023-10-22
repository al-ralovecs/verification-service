<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Message;

use Component\Gateway\Operator\RequestInterface;

final readonly class Request implements RequestInterface
{
    public function __construct(
        private string $message,
        private string $token,
    ) {
    }

    public function message(): string
    {
        return $this->message;
    }

    public function token(): string
    {
        return $this->token;
    }
}

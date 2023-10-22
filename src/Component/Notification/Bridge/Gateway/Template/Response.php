<?php

declare(strict_types=1);

namespace Component\Notification\Bridge\Gateway\Template;

use Component\Gateway\Operator\ResponseInterface;

final readonly class Response implements ResponseInterface
{
    public function __construct(
        private string $body,
    ) {
    }

    public function body(): string
    {
        return $this->body;
    }
}

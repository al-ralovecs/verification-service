<?php

declare(strict_types=1);

namespace Component\Notification\Bridge\Gateway\Template;

use Component\Gateway\Operator\RequestInterface;

final readonly class Request implements RequestInterface
{
    public function __construct(
        private string $slug,
        private string $code,
    ) {
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function code(): string
    {
        return $this->code;
    }
}

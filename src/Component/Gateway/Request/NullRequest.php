<?php

declare(strict_types=1);

namespace Component\Gateway\Request;

use Component\Gateway\Operator\RequestInterface;

final class NullRequest implements RequestInterface
{
    public function __toString(): string
    {
        return '';
    }
}

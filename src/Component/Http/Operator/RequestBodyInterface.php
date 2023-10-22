<?php

declare(strict_types=1);

namespace Component\Http\Operator;

use Psr\Http\Message\StreamInterface;

interface RequestBodyInterface
{
    public function toStream(): StreamInterface;
}

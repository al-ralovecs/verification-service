<?php

declare(strict_types=1);

namespace Component\Http\Bridge\Psr7\Transformer;

use Component\Http\Operator\UriInterface;
use Psr\Http\Message\UriInterface as Psr7UriInterface;

interface UriTransformerInterface
{
    public function transform(UriInterface $uri): Psr7UriInterface;
}

<?php

declare(strict_types=1);

namespace Component\Http\Operator;

use Stringable;

interface UriInterface extends Stringable
{
    public function path(): string;

    public function query(): string;
}

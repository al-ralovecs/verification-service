<?php

declare(strict_types=1);

namespace Component\Http\Operator;

use Component\Http\Enum\Method;

interface RequestInterface
{
    public function method(): Method;

    public function uri(): UriInterface;

    public function body(): ?RequestBodyInterface;

    /**
     * @return array<string, array<string>|string>
     */
    public function headers(): array;

    /**
     * @return array<string, mixed>
     */
    public function options(): array;
}

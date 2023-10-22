<?php

declare(strict_types=1);

namespace Component\Http\Uri;

use Component\Http\Operator\UriInterface;
use Stringable;

final readonly class Uri implements Stringable, UriInterface
{
    /**
     * @param array<string, mixed> $queryParams
     */
    public function __construct(
        private string $path,
        private array $queryParams = [],
    ) {
    }

    public function path(): string
    {
        return $this->path;
    }

    public function query(): string
    {
        return http_build_query($this->queryParams);
    }

    public function __toString(): string
    {
        return $this->path() . (('' !== $query = $this->query()) ? sprintf('?%s', $query) : '');
    }
}

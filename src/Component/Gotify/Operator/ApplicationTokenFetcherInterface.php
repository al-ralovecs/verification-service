<?php

declare(strict_types=1);

namespace Component\Gotify\Operator;

interface ApplicationTokenFetcherInterface
{
    public function try(string $appName, string $recipient): ?string;
}

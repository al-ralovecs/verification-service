<?php

declare(strict_types=1);

namespace Component\Common\Operator;

interface StatusCodeAwareExceptionInterface
{
    public function statusCode(): int;
}

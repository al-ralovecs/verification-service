<?php

declare(strict_types=1);

namespace Component\Gotify\Operator;

interface RecipientPingerInterface
{
    public function try(string $recipient): void;
}

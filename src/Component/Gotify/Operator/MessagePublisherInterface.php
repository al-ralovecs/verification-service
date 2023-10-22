<?php

declare(strict_types=1);

namespace Component\Gotify\Operator;

interface MessagePublisherInterface
{
    public function publish(string $content, string $token): void;
}

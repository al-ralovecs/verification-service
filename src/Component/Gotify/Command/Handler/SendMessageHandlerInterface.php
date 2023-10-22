<?php

declare(strict_types=1);

namespace Component\Gotify\Command\Handler;

use Component\Gotify\Command\SendMessageCommandInterface;

interface SendMessageHandlerInterface
{
    public function __invoke(SendMessageCommandInterface $command): void;
}

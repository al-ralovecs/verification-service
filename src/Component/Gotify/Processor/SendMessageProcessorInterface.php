<?php

declare(strict_types=1);

namespace Component\Gotify\Processor;

use Component\Gotify\Command\SendMessageCommandInterface;

interface SendMessageProcessorInterface
{
    public function __invoke(SendMessageCommandInterface $command): void;
}

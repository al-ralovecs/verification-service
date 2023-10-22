<?php

declare(strict_types=1);

namespace Component\MessageRecorder\Bridge\Messenger\Resolver;

use Symfony\Component\Messenger\MessageBusInterface;

interface MessageBusResolverInterface
{
    public function bus(object $message): ?MessageBusInterface;
}

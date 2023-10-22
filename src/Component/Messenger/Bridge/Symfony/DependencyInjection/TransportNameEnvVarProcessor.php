<?php

declare(strict_types=1);

namespace Component\Messenger\Bridge\Symfony\DependencyInjection;

use Closure;
use Component\Messenger\Bridge\RabbitMq\Transformer\QueueNameToStringTransformer;
use Component\Messenger\Enum\QueueName;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

final readonly class TransportNameEnvVarProcessor implements EnvVarProcessorInterface
{
    public function __construct(
        private QueueNameToStringTransformer $queueNameToStringTransformer,
    ) {
    }

    public function getEnv(string $prefix, string $name, Closure $getEnv): string
    {
        $queueName = $this->queueNameToStringTransformer->transform(QueueName::byName(strtoupper($name)));

        return match ($prefix) {
            'queue_name', 'exchange_name' => $queueName,
            'failure_queue_name', 'failure_exchange_name' => sprintf('%s_failures', $queueName),
            default => throw new RuntimeException(sprintf('Cannot handle "%s" prefix.', $prefix)),
        };
    }

    public static function getProvidedTypes(): array
    {
        return [
            'queue_name' => 'string',
            'failure_queue_name' => 'string',
            'exchange_name' => 'string',
            'failure_exchange_name' => 'string',
        ];
    }
}

<?php

declare(strict_types=1);

namespace Component\Messenger\Bridge\RabbitMq\Transformer;

use Component\Messenger\Enum\QueueName;

final class QueueNameToStringTransformer
{
    private string $prefix;

    public function __construct(?string $prefix)
    {
        $this->prefix = strtolower(trim($prefix ?? ''));
        if ('' !== $this->prefix) {
            $this->prefix = rtrim($this->prefix, '_') . '_';
        }
    }

    public function transform(QueueName $queueName): string
    {
        return sprintf('%s%s', $this->prefix, $queueName->getValue());
    }

    public function reverseTransform(string $queueName): QueueName
    {
        if (('' !== $this->prefix) && str_starts_with($queueName, $this->prefix)) {
            $queueName = substr($queueName, strlen($this->prefix));
        }

        return QueueName::byValue($queueName);
    }
}

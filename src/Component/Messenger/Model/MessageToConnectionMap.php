<?php

declare(strict_types=1);

namespace Component\Messenger\Model;

use InvalidArgumentException;

final class MessageToConnectionMap implements MessageToConnectionMapInterface
{
    /**
     * @var array<string, string[]>
     */
    private array $map = [];

    /**
     * @param array<string, string> $availableConnections
     * @param array<string, string[]> $messageToConnectionMap
     */
    public function __construct(
        private readonly array $availableConnections,
        array $messageToConnectionMap,
    ) {
        foreach ($messageToConnectionMap as $messageClass => $connections) {
            $this->set($messageClass, ...$connections);
        }
    }

    /**
     * @return string[]
     */
    public function connections(string $messageClass): array
    {
        return $this->map[$messageClass] ?? [];
    }

    private function set(string $messageClass, string ...$connections): void
    {
        assert(class_exists($messageClass), new InvalidArgumentException(sprintf('Class %s does not exist.', $messageClass)));
        foreach ($connections as $connection) {
            assert(
                array_key_exists($connection, $this->availableConnections),
                new InvalidArgumentException(sprintf('Connection %s is not supported.', $connection)),
            );
        }

        $this->map[$messageClass] = $connections;
    }
}

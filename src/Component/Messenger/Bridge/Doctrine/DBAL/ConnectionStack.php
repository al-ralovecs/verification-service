<?php

declare(strict_types=1);

namespace Component\Messenger\Bridge\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use InvalidArgumentException;
use SN\Collection\Enum\Priority;
use SN\Collection\Model\PrioritizedCollection;
use Throwable;

final readonly class ConnectionStack
{
    /** @var PrioritizedCollection<Connection> */
    private PrioritizedCollection $connections;

    public function __construct()
    {
        $this->connections = new PrioritizedCollection();
    }

    public function add(object $connection, int $priority = Priority::NORMAL): void
    {
        assert(
            $connection instanceof Connection,
            new InvalidArgumentException(sprintf('Connection must be an instance of %s, %s given.', Connection::class, get_class($connection))),
        );
        $this->connections->add($connection, $priority);
    }

    /**
     * @throws Throwable
     */
    public function transactional(callable $fn): mixed
    {
        $this->connections->forAll(function (Connection $connection): void {
            $connection->beginTransaction();
        });

        try {
            $return = $fn();

            $this->connections->forAll(function (Connection $connection): void {
                $connection->commit();
            });

            return $return;
        } catch (Throwable $exception) {
            $this->connections->forAll(function (Connection $connection): void {
                $connection->rollBack();
            });

            throw $exception;
        }
    }
}

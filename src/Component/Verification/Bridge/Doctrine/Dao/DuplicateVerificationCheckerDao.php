<?php

declare(strict_types=1);

namespace Component\Verification\Bridge\Doctrine\Dao;

use Component\Verification\Operator\VerificationSubjectInterface;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;

final readonly class DuplicateVerificationCheckerDao
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    /**
     * @throws DBALException
     */
    public function duplicateExists(VerificationSubjectInterface $subject, DateTimeImmutable $createdSince, int $maxAttempts): bool
    {
        $result = ($qb = $this->connection->createQueryBuilder())
            ->select('1')
            ->from('verification', 'v')
            ->andWhere($qb->expr()->eq('v.identity', $qb->expr()->literal($subject->identity())))
            ->andWhere($qb->expr()->eq('v.type', $qb->expr()->literal($subject->type()->value)))
            ->andWhere($qb->expr()->gte('v.created_at', $qb->expr()->literal($createdSince->format('Y-m-d H:i:s'))))
            ->andWhere($qb->expr()->lt('v.attempts', $qb->expr()->literal($maxAttempts)))
            ->executeQuery()
            ->fetchOne();

        return false !== $result;
    }
}

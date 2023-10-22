<?php

declare(strict_types=1);

namespace Component\Verification\Bridge\Doctrine\Repository;

use Component\Verification\Exception\VerificationNotFoundException;
use Component\Verification\Model\Verification;
use Component\Verification\Model\VerificationInterface;
use Component\Verification\Operator\VerificationFetcherInterface;
use Component\Verification\Operator\VerificationPersisterInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VerificationInterface>
 */
final class VerificationRepository extends ServiceEntityRepository implements VerificationFetcherInterface, VerificationPersisterInterface
{
    public function __construct(ManagerRegistry $manager)
    {
        parent::__construct($manager, Verification::class);
    }

    public function getById(string $id): VerificationInterface
    {
        /** @var VerificationInterface|null $verification */
        $verification = $this->find($id);
        if (null === $verification) {
            throw new VerificationNotFoundException($id);
        }

        return $verification;
    }

    public function save(VerificationInterface ...$verifications): void
    {
        foreach ($verifications as $verification) {
            $this->getEntityManager()->persist($verification);
        }

        $this->getEntityManager()->flush();
    }
}

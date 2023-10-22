<?php

declare(strict_types=1);

namespace Component\Notification\Bridge\Doctrine\Repository;

use Component\Dao\Exception\LockWaitTimeoutException;
use Component\Notification\Exception\NotificationNotFoundException;
use Component\Notification\Model\Notification;
use Component\Notification\Model\NotificationInterface;
use Component\Notification\Operator\NotificationFetcherInterface;
use Component\Notification\Operator\NotificationPersisterInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\LockWaitTimeoutException as DbalLockWaitTimeoutException;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificationInterface>
 */
final class NotificationRepository extends ServiceEntityRepository implements NotificationFetcherInterface, NotificationPersisterInterface
{
    public function __construct(ManagerRegistry $manager)
    {
        parent::__construct($manager, Notification::class);
    }

    public function getById(string $notificationId, bool $lock = false): NotificationInterface
    {
        try {
            /** @var NotificationInterface|null $notification */
            $notification = $this->find($notificationId, $lock ? LockMode::PESSIMISTIC_WRITE : null);
        } catch (DbalLockWaitTimeoutException $exception) {
            throw LockWaitTimeoutException::fromDbalException($exception);
        }

        if (null === $notification) {
            throw NotificationNotFoundException::missingId($notificationId);
        }

        return $notification;
    }

    public function save(NotificationInterface ...$notifications): void
    {
        foreach ($notifications as $notification) {
            $this->getEntityManager()->persist($notification);
        }

        $this->getEntityManager()->flush();
    }
}

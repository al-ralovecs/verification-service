<?php

declare(strict_types=1);

namespace Component\MessageRecorder\Bridge\Doctrine\EventListener;

use Component\MessageRecorder\Bridge\Messenger\Event\EntityMessagePreDispatchEvent;
use Component\MessageRecorder\Bridge\Messenger\Resolver\MessageBusResolverInterface;
use Component\MessageRecorder\Exception\MessageBusNotResolvedException;
use Component\MessageRecorder\Model\ContainsRecordedMessages;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class DispatchEntityMessagesDoctrineSubscriber implements EventSubscriber
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private MessageBusResolverInterface $busResolver,
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postFlush,
        ];
    }

    public function postFlush(PostFlushEventArgs $eventArgs): void
    {
        foreach ($this->entities($eventArgs) as $entity) {
            $this->dispatchMessagesFromEntity($entity);
        }
    }

    private function dispatchMessagesFromEntity(ContainsRecordedMessages $entity): void
    {
        foreach ($entity->recordedMessages() as $message) {
            if (null === $bus = $this->busResolver->bus($message)) {
                throw new MessageBusNotResolvedException($message);
            }

            $envelope = Envelope::wrap($message, [new DispatchAfterCurrentBusStamp()]);
            $event = new EntityMessagePreDispatchEvent($entity, $envelope);

            $this->dispatcher->dispatch($event);
            $bus->dispatch($event->envelope());
        }

        $entity->eraseMessages();
    }

    /**
     * @return iterable<ContainsRecordedMessages>
     */
    private function entities(PostFlushEventArgs $eventArgs): iterable
    {
        $uow = $eventArgs->getObjectManager()->getUnitOfWork();
        foreach ($uow->getIdentityMap() as $entities) {
            foreach ($entities as $entity) {
                if ($entity instanceof ContainsRecordedMessages) {
                    yield $entity;
                }
            }
        }
    }
}

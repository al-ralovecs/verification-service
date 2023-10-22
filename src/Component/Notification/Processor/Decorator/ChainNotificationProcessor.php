<?php

declare(strict_types=1);

namespace Component\Notification\Processor\Decorator;

use Component\Notification\Model\NotificationInterface;
use Component\Notification\Processor\NotificationProcessorInterface;
use SN\Collection\Enum\Priority;
use SN\Collection\Model\PrioritizedCollection;

final readonly class ChainNotificationProcessor implements NotificationProcessorInterface
{
    /** @var PrioritizedCollection<NotificationProcessorInterface> */
    private PrioritizedCollection $processors;

    public function __construct()
    {
        $this->processors = new PrioritizedCollection();
    }

    public function add(NotificationProcessorInterface $notificationProcessor, int $priority = Priority::NORMAL): void
    {
        $this->processors->add($notificationProcessor, $priority);
    }

    public function __invoke(NotificationInterface $notification): void
    {
        $this->processors->forAll(
            static function (NotificationProcessorInterface $notificationProcessor) use ($notification): void {
                ($notificationProcessor)($notification);
            },
        );
    }
}

<?php

declare(strict_types=1);

namespace Component\Verification\Processor\Confirm\Decorator;

use Component\Verification\Context\ConfirmVerificationContextInterface;
use Component\Verification\Model\VerificationInterface;
use Component\Verification\Processor\Confirm\VerificationProcessorInterface;
use SN\Collection\Enum\Priority;
use SN\Collection\Model\PrioritizedCollection;

final readonly class ChainVerificationProcessor implements VerificationProcessorInterface
{
    /** @var PrioritizedCollection<VerificationProcessorInterface> */
    private PrioritizedCollection $procesors;

    public function __construct()
    {
        $this->procesors = new PrioritizedCollection();
    }

    public function add(VerificationProcessorInterface $verificationProcessor, int $priority = Priority::NORMAL): void
    {
        $this->procesors->add($verificationProcessor, $priority);
    }

    public function __invoke(VerificationInterface $verification, ConfirmVerificationContextInterface $context): void
    {
        $this->procesors->forAll(
            static function (VerificationProcessorInterface $verificationProcessor) use ($verification, $context): void {
                ($verificationProcessor)($verification, $context);
            },
        );
    }
}

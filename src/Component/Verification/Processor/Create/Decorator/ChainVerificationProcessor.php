<?php

declare(strict_types=1);

namespace Component\Verification\Processor\Create\Decorator;

use Component\Verification\Model\VerificationInterface;
use Component\Verification\Processor\Create\VerificationProcessorInterface;
use SN\Collection\Enum\Priority;
use SN\Collection\Model\PrioritizedCollection;

final readonly class ChainVerificationProcessor implements VerificationProcessorInterface
{
    /** @var PrioritizedCollection<VerificationProcessorInterface> */
    private PrioritizedCollection $processors;

    public function __construct()
    {
        $this->processors = new PrioritizedCollection();
    }

    public function add(VerificationProcessorInterface $verificationProcessor, int $priority = Priority::NORMAL): void
    {
        $this->processors->add($verificationProcessor, $priority);
    }

    public function __invoke(VerificationInterface $verification): void
    {
        $this->processors->forAll(
            static function (VerificationProcessorInterface $verificationProcessor) use ($verification): void {
                ($verificationProcessor)($verification);
            },
        );
    }
}

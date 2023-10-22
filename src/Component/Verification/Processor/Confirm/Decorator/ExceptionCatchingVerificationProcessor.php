<?php

declare(strict_types=1);

namespace Component\Verification\Processor\Confirm\Decorator;

use Component\Verification\Context\ConfirmVerificationContextInterface;
use Component\Verification\Model\VerificationInterface;
use Component\Verification\Operator\VerificationPersisterInterface;
use Component\Verification\Processor\Confirm\VerificationProcessorInterface;
use Throwable;

final readonly class ExceptionCatchingVerificationProcessor implements VerificationProcessorInterface
{
    public function __construct(
        private VerificationProcessorInterface $decoratedProcessor,
        private VerificationPersisterInterface $verificationPersister,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(VerificationInterface $verification, ConfirmVerificationContextInterface $context): void
    {
        try {
            ($this->decoratedProcessor)($verification, $context);
        } catch (Throwable $throwable) {
            $verification->failed($context->code());
            $this->verificationPersister->save($verification);

            throw $throwable;
        }
    }
}

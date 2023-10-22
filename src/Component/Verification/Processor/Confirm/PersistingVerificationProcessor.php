<?php

declare(strict_types=1);

namespace Component\Verification\Processor\Confirm;

use Component\Verification\Context\ConfirmVerificationContextInterface;
use Component\Verification\Model\VerificationInterface;
use Component\Verification\Operator\VerificationPersisterInterface;

final readonly class PersistingVerificationProcessor implements VerificationProcessorInterface
{
    public function __construct(
        private VerificationPersisterInterface $verificationPersister,
    ) {
    }

    public function __invoke(VerificationInterface $verification, ConfirmVerificationContextInterface $context): void
    {
        $this->verificationPersister->save($verification);
    }
}

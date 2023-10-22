<?php

declare(strict_types=1);

namespace Component\Verification\Processor\Create;

use Component\Verification\Model\VerificationInterface;
use Component\Verification\Operator\VerificationPersisterInterface;

final readonly class PersistingVerificationProcessor implements VerificationProcessorInterface
{
    public function __construct(
        private VerificationPersisterInterface $verificationPersister,
    ) {
    }

    public function __invoke(VerificationInterface $verification): void
    {
        $this->verificationPersister->save($verification);
    }
}

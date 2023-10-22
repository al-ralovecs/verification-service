<?php

declare(strict_types=1);

namespace Component\Verification\Command\Handler;

use Component\Verification\Command\ConfirmVerificationCommand;
use Component\Verification\Context\ConfirmVerificationContext;
use Component\Verification\Operator\VerificationFetcherInterface;
use Component\Verification\Processor\Confirm\VerificationProcessorInterface;

final readonly class ConfirmVerificationHandler
{
    public function __construct(
        private VerificationFetcherInterface $verificationFetcher,
        private VerificationProcessorInterface $verificationConfirmer,
    ) {
    }

    public function __invoke(ConfirmVerificationCommand $command): void
    {
        $verification = $this->verificationFetcher->getById($command->id());
        $context = new ConfirmVerificationContext($command->code(), $command->userInfo());

        ($this->verificationConfirmer)($verification, $context);
    }
}

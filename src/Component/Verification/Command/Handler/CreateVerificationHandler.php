<?php

declare(strict_types=1);

namespace Component\Verification\Command\Handler;

use Component\Verification\Command\CreateVerificationCommand;
use Component\Verification\Generator\VerificationCodeGeneratorInterface;
use Component\Verification\Model\Verification;
use Component\Verification\Processor\Create\VerificationProcessorInterface;
use Exception;

final readonly class CreateVerificationHandler
{
    public function __construct(
        private VerificationCodeGeneratorInterface $codeGenerator,
        private VerificationProcessorInterface $verificationProcessor,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(CreateVerificationCommand $command): void
    {
        $verification = Verification::create($command, $this->codeGenerator->code());

        ($this->verificationProcessor)($verification);
    }
}

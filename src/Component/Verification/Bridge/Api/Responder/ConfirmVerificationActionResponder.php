<?php

declare(strict_types=1);

namespace Component\Verification\Bridge\Api\Responder;

use Component\Verification\Bridge\Api\Request\ConfirmVerificationRequest;
use Component\Verification\Bridge\Api\Request\VerificationRequestContext;
use Component\Verification\Command\ConfirmVerificationCommand;
use Component\Verification\Command\Handler\ConfirmVerificationHandler;
use Symfony\Component\HttpFoundation\Response;

final readonly class ConfirmVerificationActionResponder
{
    public function __construct(
        private ConfirmVerificationHandler $confirmVerificationHandler,
    ) {
    }

    public function __invoke(
        string $verificationId,
        ConfirmVerificationRequest $request,
        VerificationRequestContext $context,
    ): Response {
        $command = new ConfirmVerificationCommand(
            $verificationId,
            $context->userInfo(),
            $request->code(),
        );

        ($this->confirmVerificationHandler)($command);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}

<?php

declare(strict_types=1);

namespace Application\Controller\Verification;

use Component\Verification\Bridge\Api\Request\ConfirmVerificationRequest;
use Component\Verification\Bridge\Api\Request\VerificationRequestContext;
use Component\Verification\Bridge\Api\Responder\ConfirmVerificationActionResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/verifications/{verificationId}/confirm',
    name: 'app.api.verification.confirm',
    methods: [Request::METHOD_PUT],
)]
final readonly class ConfirmVerificationAction
{
    public function __construct(
        private ConfirmVerificationActionResponder $confirmVerificationActionResponder,
    ) {
    }

    public function __invoke(
        string $verificationId,
        #[MapRequestPayload()] ConfirmVerificationRequest $request,
        Request $httpRequest,
    ): Response {
        $context = new VerificationRequestContext($httpRequest->getClientIp(), $httpRequest->headers);

        return ($this->confirmVerificationActionResponder)($verificationId, $request, $context);
    }
}

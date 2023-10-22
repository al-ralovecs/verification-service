<?php

declare(strict_types=1);

namespace Application\Controller\Verification;

use Component\Verification\Bridge\Api\Request\CreateVerificationRequest;
use Component\Verification\Bridge\Api\Request\VerificationRequestContext;
use Component\Verification\Bridge\Api\Responder\CreateVerificationActionResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/verifications',
    name: 'app.api.verification.create',
    methods: [Request::METHOD_POST],
)]
final readonly class CreateVerificationAction
{
    public function __construct(
        private CreateVerificationActionResponder $createVerificationActionResponder,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload()] CreateVerificationRequest $request,
        Request $httpRequest,
    ): Response {
        $context = new VerificationRequestContext($httpRequest->getClientIp(), $httpRequest->headers);

        return ($this->createVerificationActionResponder)($request, $context);
    }
}

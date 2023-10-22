<?php

declare(strict_types=1);

namespace Component\Verification\Bridge\Api\Responder;

use Component\Verification\Bridge\Api\Request\CreateVerificationRequest;
use Component\Verification\Bridge\Api\Request\VerificationRequestContext;
use Component\Verification\Command\CreateVerificationCommand;
use Component\Verification\Command\Handler\CreateVerificationHandler;
use DateTimeImmutable;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final readonly class CreateVerificationActionResponder
{
    public function __construct(
        private CreateVerificationHandler $createVerificationHandler,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(CreateVerificationRequest $request, VerificationRequestContext $context): Response
    {
        $command = new CreateVerificationCommand(
            (string) Uuid::v7(),
            $request->subject(),
            $context->userInfo(),
            new DateTimeImmutable(),
        );

        ($this->createVerificationHandler)($command);

        return new JsonResponse([
            'id' => $command->id(),
        ], Response::HTTP_CREATED);
    }
}

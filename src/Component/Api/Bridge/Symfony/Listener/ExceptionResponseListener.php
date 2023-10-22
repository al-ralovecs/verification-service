<?php

declare(strict_types=1);

namespace Component\Api\Bridge\Symfony\Listener;

use Component\Common\Operator\StatusCodeAwareExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

final readonly class ExceptionResponseListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        $statusCode = $this->statusCode($throwable);

        $event->setResponse(new Response(500 === $statusCode ? $throwable->getMessage() : null, $statusCode));
    }

    private function statusCode(Throwable $throwable): int
    {
        return match (true) {
            $throwable instanceof HttpExceptionInterface => $throwable->getStatusCode(),
            $throwable instanceof StatusCodeAwareExceptionInterface => $throwable->statusCode(),
            !array_key_exists($throwable->getCode(), Response::$statusTexts) => 500,
            default => $throwable->getCode(),
        };
    }
}

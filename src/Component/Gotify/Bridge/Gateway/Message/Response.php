<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Message;

use Component\Gateway\Operator\ResponseInterface;
use Component\Gotify\Dto\MessageDto;

final readonly class Response implements ResponseInterface
{
    public function __construct(
        private MessageDto $message,
    ) {
    }

    public function message(): MessageDto
    {
        return $this->message;
    }
}

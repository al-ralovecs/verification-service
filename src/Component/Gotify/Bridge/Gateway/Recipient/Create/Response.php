<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Recipient\Create;

use Component\Gateway\Operator\ResponseInterface;
use Component\Gotify\Dto\RecipientDto;

final readonly class Response implements ResponseInterface
{
    public function __construct(
        private RecipientDto $recipient,
    ) {
    }

    public function recipient(): RecipientDto
    {
        return $this->recipient;
    }
}

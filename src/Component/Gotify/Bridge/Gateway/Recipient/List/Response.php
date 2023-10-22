<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Recipient\List;

use Component\Gateway\Operator\ResponseInterface;
use Component\Gotify\Dto\RecipientDtoCollection;

final readonly class Response implements ResponseInterface
{
    public function __construct(
        private ?RecipientDtoCollection $recipients,
    ) {
    }

    public function recipients(): ?RecipientDtoCollection
    {
        return $this->recipients;
    }
}

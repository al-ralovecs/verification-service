<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Recipient;

use Component\Gotify\Bridge\Gateway\Recipient\Create\Gateway;
use Component\Gotify\Bridge\Gateway\Recipient\Create\Request;
use Component\Gotify\Exception\RecipientDoesNotExistException;
use Component\Gotify\Operator\RecipientPingerInterface;
use Throwable;

final readonly class CreateRecipientPinger implements RecipientPingerInterface
{
    public function __construct(
        private Gateway $gateway,
        private string $pass,
    ) {
    }

    public function try(string $recipient): void
    {
        try {
            $this->gateway->request(new Request($recipient, $this->pass));
        } catch (Throwable $throwable) {
            throw new RecipientDoesNotExistException($recipient);
        }
    }
}

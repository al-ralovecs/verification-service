<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Recipient;

use Component\Gateway\Operator\GatewayExceptionInterface;
use Component\Gotify\Bridge\Gateway\Recipient\List\Gateway;
use Component\Gotify\Bridge\Gateway\Recipient\List\Request;
use Component\Gotify\Exception\RecipientDoesNotExistException;
use Component\Gotify\Operator\RecipientPingerInterface;

final readonly class ListRecipientPinger implements RecipientPingerInterface
{
    public function __construct(
        private Gateway $gateway,
    ) {
    }

    /**
     * @throws GatewayExceptionInterface
     */
    public function try(string $recipient): void
    {
        $response = $this->gateway->request(new Request());

        if (null === $response->recipients()) {
            throw new RecipientDoesNotExistException($recipient);
        }

        if ($response->recipients()->has($recipient)) {
            return;
        }

        throw new RecipientDoesNotExistException($recipient);
    }
}

<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Message;

use Component\Gateway\Operator\GatewayExceptionInterface;
use Component\Gotify\Bridge\Gateway\Message\Gateway;
use Component\Gotify\Bridge\Gateway\Message\Request;
use Component\Gotify\Operator\MessagePublisherInterface;

final readonly class MessagePublisher implements MessagePublisherInterface
{
    public function __construct(
        private Gateway $gateway,
    ) {
    }

    /**
     * @throws GatewayExceptionInterface
     */
    public function publish(string $content, string $token): void
    {
        $this->gateway->request(new Request($content, $token));
    }
}

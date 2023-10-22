<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Application;

use Component\Gateway\Operator\GatewayExceptionInterface;
use Component\Gotify\Bridge\Gateway\Application\Create\Gateway;
use Component\Gotify\Bridge\Gateway\Application\Create\Request;
use Component\Gotify\Operator\ApplicationTokenFetcherInterface;

final readonly class CreateApplicationTokenFetcher implements ApplicationTokenFetcherInterface
{
    public function __construct(
        private Gateway $gateway,
        private string $recipientPassword,
    ) {
    }

    /**
     * @throws GatewayExceptionInterface
     */
    public function try(string $appName, string $recipient): ?string
    {
        $response = $this->gateway->request(new Request($appName, $appName, $recipient, $this->recipientPassword));

        return $response->application()->token();
    }
}

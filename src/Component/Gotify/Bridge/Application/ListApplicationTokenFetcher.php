<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Application;

use Component\Gateway\Operator\GatewayExceptionInterface;
use Component\Gotify\Bridge\Gateway\Application\List\Gateway;
use Component\Gotify\Bridge\Gateway\Application\List\Request;
use Component\Gotify\Dto\ApplicationDto;
use Component\Gotify\Exception\FailedToObtainAppTokenException;
use Component\Gotify\Operator\ApplicationTokenFetcherInterface;

final readonly class ListApplicationTokenFetcher implements ApplicationTokenFetcherInterface
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
        $response = $this->gateway->request(new Request($recipient, $this->recipientPassword));
        if (null === $response->applications()) {
            throw new FailedToObtainAppTokenException($recipient);
        }

        if (false === $response->applications()->has($appName)) {
            throw new FailedToObtainAppTokenException($recipient);
        }

        /** @var ApplicationDto $application */
        $application = $response->applications()
            ->get($appName);

        return $application->token();
    }
}

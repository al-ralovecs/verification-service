<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Recipient\List;

use Component\Gateway\Gateway\AbstractGetGateway;
use Component\Gateway\Operator\RequestInterface;
use Component\Gateway\Response\Transformer\ClientResponseTransformerInterface;
use Component\Http\Operator\HttpClientInterface;
use Component\Http\Uri\Uri;

/**
 * @method Response request(RequestInterface $request)
 */
final readonly class Gateway extends AbstractGetGateway
{
    public function __construct(
        HttpClientInterface $client,
        ClientResponseTransformerInterface $clientResponseTransformer,
        private string $userName,
        private string $userPass,
    ) {
        parent::__construct($client, $clientResponseTransformer);
    }

    protected function uri(RequestInterface $request): Uri
    {
        return new Uri('/user');
    }

    /**
     * @return array<string, string>
     */
    protected function headers(RequestInterface $request): array
    {
        return [
            'accept' => 'application/json;version=1.0',
            'content-type' => 'application/json',
            'Authorization' => sprintf('Basic %s', $this->credentials()),
        ];
    }

    private function credentials(): string
    {
        return base64_encode(sprintf('%s:%s', $this->userName, $this->userPass));
    }
}

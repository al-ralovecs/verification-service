<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Application\List;

use Component\Gateway\Exception\InvalidRequestException;
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
    ) {
        parent::__construct($client, $clientResponseTransformer);
    }

    protected function uri(RequestInterface $request): Uri
    {
        return new Uri('/application');
    }

    /**
     * @return array<string, string>
     */
    protected function headers(RequestInterface $request): array
    {
        if (!$request instanceof Request) {
            throw new InvalidRequestException(Request::class, get_class($request));
        }

        return [
            'accept' => 'application/json;version=1.0',
            'content-type' => 'application/json',
            'Authorization' => sprintf('Basic %s', $this->credentials($request)),
        ];
    }

    private function credentials(Request $request): string
    {
        return base64_encode(sprintf('%s:%s', $request->recipient(), $request->password()));
    }
}

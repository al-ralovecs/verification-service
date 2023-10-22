<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Message;

use Component\Gateway\Exception\InvalidRequestException;
use Component\Gateway\Gateway\AbstractPostGateway;
use Component\Gateway\Operator\RequestInterface;
use Component\Gateway\Request\Factory\RequestBodyFactoryInterface;
use Component\Gateway\Response\Transformer\ClientResponseTransformerInterface;
use Component\Http\Operator\HttpClientInterface;
use Component\Http\Uri\Uri;

/**
 * @method Response request(Request $request)
 */
final readonly class Gateway extends AbstractPostGateway
{
    public function __construct(
        HttpClientInterface $client,
        ClientResponseTransformerInterface $clientResponseTransformer,
        RequestBodyFactoryInterface $requestBodyFactory,
    ) {
        parent::__construct($client, $clientResponseTransformer, $requestBodyFactory);
    }

    protected function uri(RequestInterface $request): Uri
    {
        return new Uri('/message');
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
            'Authorization' => sprintf('Bearer %s', $request->token()),
        ];
    }
}

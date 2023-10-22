<?php

declare(strict_types=1);

namespace Component\Notification\Bridge\Gateway\Template;

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
        if (!$request instanceof Request) {
            throw new InvalidRequestException(Request::class, get_class($request));
        }

        return new Uri('/templates/render');
    }

    /**
     * @return array<string, string>
     */
    protected function headers(RequestInterface $request): array
    {
        return [
            'content-type' => 'application/json',
        ];
    }
}

<?php

declare(strict_types=1);

namespace Component\Gateway\Gateway;

use Component\Gateway\Operator\RequestInterface;
use Component\Gateway\Request\Factory\RequestBodyFactoryInterface;
use Component\Gateway\Response\Transformer\ClientResponseTransformerInterface;
use Component\Http\Enum\Method;
use Component\Http\Operator\HttpClientInterface;
use Component\Http\Operator\RequestBodyInterface;

abstract readonly class AbstractPostGateway extends AbstractRequestGateway
{
    public function __construct(
        HttpClientInterface $client,
        ClientResponseTransformerInterface $clientResponseTransformer,
        private RequestBodyFactoryInterface $requestBodyFactory,
    ) {
        parent::__construct($client, $clientResponseTransformer);
    }

    protected function body(RequestInterface $request): ?RequestBodyInterface
    {
        return $this->requestBodyFactory->create($request);
    }

    protected function method(RequestInterface $request): Method
    {
        return Method::POST;
    }
}

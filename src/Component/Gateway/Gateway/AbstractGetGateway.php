<?php

declare(strict_types=1);

namespace Component\Gateway\Gateway;

use Component\Gateway\Operator\RequestInterface;
use Component\Gateway\Operator\ResponseInterface;
use Component\Gateway\Request\NullRequest;
use Component\Http\Enum\Method;
use Component\Http\Operator\RequestBodyInterface;

abstract readonly class AbstractGetGateway extends AbstractRequestGateway
{
    public function request(RequestInterface $request = null): ResponseInterface
    {
        return parent::request($request ?? new NullRequest());
    }

    protected function body(RequestInterface $request): ?RequestBodyInterface
    {
        return null;
    }

    protected function method(RequestInterface $request): Method
    {
        return Method::GET;
    }
}

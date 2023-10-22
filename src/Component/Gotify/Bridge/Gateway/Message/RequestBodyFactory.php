<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Message;

use Component\Gateway\Exception\InvalidRequestException;
use Component\Gateway\Operator\RequestInterface;
use Component\Gateway\Request\Factory\RequestBodyFactoryInterface;
use Component\Http\Operator\RequestBodyInterface;

final class RequestBodyFactory implements RequestBodyFactoryInterface
{
    public function create(RequestInterface $request): RequestBodyInterface
    {
        if (!$request instanceof Request) {
            throw new InvalidRequestException(Request::class, get_class($request));
        }

        return new RequestBody(
            $request->message(),
        );
    }
}

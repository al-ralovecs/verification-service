<?php

declare(strict_types=1);

namespace Component\Http\Operator;

use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    /**
     * @throws HttpClientExceptionInterface
     */
    public function send(RequestInterface $request): ResponseInterface;
}

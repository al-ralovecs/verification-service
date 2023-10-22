<?php

declare(strict_types=1);

namespace Component\Gateway\Operator;

interface GatewayInterface
{
    /**
     * @throws GatewayExceptionInterface
     */
    public function request(RequestInterface $request): ResponseInterface;
}

<?php

declare(strict_types=1);

namespace Component\Gateway\Request\Factory;

use Component\Gateway\Operator\RequestInterface;
use Component\Http\Operator\RequestBodyInterface;

interface RequestBodyFactoryInterface
{
    public function create(RequestInterface $request): RequestBodyInterface;
}

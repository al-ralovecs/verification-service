<?php

declare(strict_types=1);

namespace Component\Http\Request\Body;

use Component\Http\Operator\RequestBodyInterface;
use GuzzleHttp\Psr7\Utils;
use JsonSerializable;
use Psr\Http\Message\StreamInterface;

abstract readonly class JsonRequestBody implements RequestBodyInterface, JsonSerializable
{
    public function toStream(): StreamInterface
    {
        return Utils::streamFor(\json_encode($this, JSON_THROW_ON_ERROR));
    }
}

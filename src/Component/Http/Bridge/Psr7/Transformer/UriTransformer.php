<?php

declare(strict_types=1);

namespace Component\Http\Bridge\Psr7\Transformer;

use Component\Http\Operator\UriInterface;
use GuzzleHttp\Psr7\Uri;

final class UriTransformer implements UriTransformerInterface
{
    private string $endpointUrl;

    public function __construct(string $endpointUrl)
    {
        $this->endpointUrl = $endpointUrl;
    }

    public function transform(UriInterface $uri): Uri
    {
        $psrUri = new Uri($this->endpointUrl);

        return $psrUri
            ->withPath(sprintf('%s/%s', rtrim($psrUri->getPath(), '/'), ltrim($uri->path(), '/')))
            ->withQuery($uri->query());
    }
}

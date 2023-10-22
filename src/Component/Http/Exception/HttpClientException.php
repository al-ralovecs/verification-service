<?php

declare(strict_types=1);

namespace Component\Http\Exception;

use Component\Http\Operator\HttpClientExceptionInterface;
use RuntimeException;

final class HttpClientException extends RuntimeException implements HttpClientExceptionInterface
{
}

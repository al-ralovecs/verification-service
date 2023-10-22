<?php

declare(strict_types=1);

namespace Component\Gateway\Exception;

use Component\Gateway\Operator\GatewayExceptionInterface;
use RuntimeException;

abstract class GatewayException extends RuntimeException implements GatewayExceptionInterface
{
}

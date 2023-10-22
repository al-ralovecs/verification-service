<?php

declare(strict_types=1);

namespace Component\Dao\Exception;

use Doctrine\DBAL\Exception\LockWaitTimeoutException as DbalException;
use RuntimeException;
use Symfony\Component\Messenger\Exception\RecoverableExceptionInterface;

final class LockWaitTimeoutException extends RuntimeException implements RecoverableExceptionInterface
{
    public static function fromDbalException(DbalException $exception): self
    {
        return new self($exception->getMessage(), $exception->getCode(), $exception);
    }
}

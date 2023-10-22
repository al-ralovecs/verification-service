<?php

declare(strict_types=1);

namespace Component\Messenger\Exception;

use Symfony\Component\Messenger\Exception\UnrecoverableExceptionInterface;

/**
 * Exceptions implementing this interface must neither
 * stop message handling process, nor
 * trigger message handling retry.
 */
interface SuppressibleMessageHandlingExceptionInterface extends UnrecoverableExceptionInterface
{
}

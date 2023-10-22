<?php

declare(strict_types=1);

namespace Component\Verification\Validator\Confirm;

use Component\Verification\Context\ConfirmVerificationContextInterface;
use Component\Verification\Exception\VerificationExpiredException;
use Component\Verification\Model\VerificationInterface;
use DateInterval;
use DateTimeImmutable;

final class ConfirmVerificationExpiredValidator implements ConfirmVerificationValidatorInterface
{
    public function __construct(
        private DateInterval $ttl,
    ) {
    }

    public function __invoke(VerificationInterface $verification, ConfirmVerificationContextInterface $context): void
    {
        if (new DateTimeImmutable() < $verification->createdAt()->add($this->ttl)) {
            return;
        }

        throw new VerificationExpiredException($verification->id());
    }
}

<?php

declare(strict_types=1);

namespace Component\Verification\Bridge\Api\Request;

use Component\Verification\Bridge\Api\Exception\VerificationRequestValidationFailedException;
use Component\Verification\Model\VerificationUserInfo;
use Component\Verification\Model\VerificationUserInfoInterface;
use Symfony\Component\HttpFoundation\HeaderBag;

final readonly class VerificationRequestContext
{
    private const HEADER_USER_AGENT = 'User-Agent';

    public function __construct(
        private ?string $ipAddress,
        private HeaderBag $headers,
    ) {
        $this->validate();
    }

    public function userInfo(): VerificationUserInfoInterface
    {
        return new VerificationUserInfo(
            $this->ipAddress ?? throw VerificationRequestValidationFailedException::unrecognizedIpAddress(),
            $this->headers->get(self::HEADER_USER_AGENT) ?? throw VerificationRequestValidationFailedException::missingHeader(self::HEADER_USER_AGENT),
        );
    }

    private function validate(): void
    {
        if (!$this->headers->has(self::HEADER_USER_AGENT)) {
            throw VerificationRequestValidationFailedException::missingHeader(self::HEADER_USER_AGENT);
        }

        if (null === $this->ipAddress) {
            throw VerificationRequestValidationFailedException::unrecognizedIpAddress();
        }
    }
}

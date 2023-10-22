<?php

declare(strict_types=1);

namespace Component\Verification\Bridge\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ConfirmVerificationRequest
{
    /**
     * @param numeric-string $code
     */
    public function __construct(
        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ])]
        public string $code,
    ) {
    }

    /**
     * @return numeric-string
     */
    public function code(): string
    {
        return $this->code;
    }
}

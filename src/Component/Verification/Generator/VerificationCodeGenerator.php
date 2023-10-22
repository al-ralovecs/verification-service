<?php

declare(strict_types=1);

namespace Component\Verification\Generator;

use Exception;

final readonly class VerificationCodeGenerator implements VerificationCodeGeneratorInterface
{
    private const CODE_MAX_LENGTH = 8;
    private const CODE_MIN_LENGTH = 4;

    /**
     * @return numeric-string
     *
     * @throws Exception
     */
    public function code(): string
    {
        /** @var numeric-string $code */
        $code = '';

        for (
            $i = 0;
            $i < random_int(self::CODE_MIN_LENGTH, self::CODE_MAX_LENGTH);
            ++$i
        ) {
            $code[$i] = random_int(0, 9);
        }

        return $code;
    }
}

<?php

declare(strict_types=1);

namespace Component\Verification\Validator\Confirm\Decorator;

use Component\Verification\Context\ConfirmVerificationContextInterface;
use Component\Verification\Model\VerificationInterface;
use Component\Verification\Validator\Confirm\ConfirmVerificationValidatorInterface;
use SN\Collection\Enum\Priority;
use SN\Collection\Model\PrioritizedCollection;

final readonly class ChainConfirmVerificationValidator implements ConfirmVerificationValidatorInterface
{
    /** @var PrioritizedCollection<ConfirmVerificationValidatorInterface> */
    private PrioritizedCollection $validators;

    public function __construct()
    {
        $this->validators = new PrioritizedCollection();
    }

    public function add(ConfirmVerificationValidatorInterface $verificationValidator, int $priority = Priority::NORMAL): void
    {
        $this->validators->add($verificationValidator, $priority);
    }

    public function __invoke(VerificationInterface $verification, ConfirmVerificationContextInterface $context): void
    {
        $this->validators->forAll(
            static function (ConfirmVerificationValidatorInterface $verificationValidator) use ($verification, $context): void {
                ($verificationValidator)($verification, $context);
            },
        );
    }
}

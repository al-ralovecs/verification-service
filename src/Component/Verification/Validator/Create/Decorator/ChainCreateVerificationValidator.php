<?php

declare(strict_types=1);

namespace Component\Verification\Validator\Create\Decorator;

use Component\Verification\Model\VerificationInterface;
use Component\Verification\Validator\Create\CreateVerificationValidatorInterface;
use SN\Collection\Enum\Priority;
use SN\Collection\Model\PrioritizedCollection;

final readonly class ChainCreateVerificationValidator implements CreateVerificationValidatorInterface
{
    /** @var PrioritizedCollection<CreateVerificationValidatorInterface> */
    private PrioritizedCollection $validators;

    public function __construct()
    {
        $this->validators = new PrioritizedCollection();
    }

    public function add(CreateVerificationValidatorInterface $verificationValidator, int $priority = Priority::NORMAL): void
    {
        $this->validators->add($verificationValidator, $priority);
    }

    public function __invoke(VerificationInterface $verification): void
    {
        $this->validators->forAll(
            static function (CreateVerificationValidatorInterface $verificationValidator) use ($verification): void {
                ($verificationValidator)($verification);
            },
        );
    }
}

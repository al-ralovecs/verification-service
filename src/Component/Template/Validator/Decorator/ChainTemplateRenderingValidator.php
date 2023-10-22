<?php

declare(strict_types=1);

namespace Component\Template\Validator\Decorator;

use Component\Template\Context\TemplateRenderingContextInterface;
use Component\Template\Model\TemplateInterface;
use Component\Template\Validator\TemplateRenderingValidatorInterface;
use SN\Collection\Enum\Priority;
use SN\Collection\Model\PrioritizedCollection;

final readonly class ChainTemplateRenderingValidator implements TemplateRenderingValidatorInterface
{
    /** @var PrioritizedCollection<TemplateRenderingValidatorInterface> */
    private PrioritizedCollection $validators;

    public function __construct()
    {
        $this->validators = new PrioritizedCollection();
    }

    public function add(TemplateRenderingValidatorInterface $validator, int $priority = Priority::NORMAL): void
    {
        $this->validators->add($validator, $priority);
    }

    public function __invoke(TemplateInterface $template, TemplateRenderingContextInterface $context): void
    {
        $this->validators->forAll(
            static function (TemplateRenderingValidatorInterface $validator) use ($template, $context): void {
                ($validator)($template, $context);
            },
        );
    }
}

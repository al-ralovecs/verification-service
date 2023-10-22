<?php

declare(strict_types=1);

namespace Component\Template\Processor\Decorator;

use Component\Template\Context\TemplateRenderingContextInterface;
use Component\Template\Model\TemplateInterface;
use Component\Template\Processor\TemplateRenderingProcessorInterface;
use Component\Template\Response\TemplateRenderingResponseInterface;
use Component\Template\Validator\TemplateRenderingValidatorInterface;

final readonly class ValidatingTemplateRenderingProcessor implements TemplateRenderingProcessorInterface
{
    public function __construct(
        private TemplateRenderingValidatorInterface $templateRenderingValidator,
        private TemplateRenderingProcessorInterface $decoratedProcessor,
    ) {
    }

    public function __invoke(TemplateInterface $template, TemplateRenderingContextInterface $context): TemplateRenderingResponseInterface
    {
        ($this->templateRenderingValidator)($template, $context);

        return ($this->decoratedProcessor)($template, $context);
    }
}

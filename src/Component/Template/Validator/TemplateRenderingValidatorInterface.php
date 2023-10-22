<?php

declare(strict_types=1);

namespace Component\Template\Validator;

use Component\Template\Context\TemplateRenderingContextInterface;
use Component\Template\Exception\RenderTemplateValidationFailedException;
use Component\Template\Model\TemplateInterface;

interface TemplateRenderingValidatorInterface
{
    /**
     * @throws RenderTemplateValidationFailedException
     */
    public function __invoke(TemplateInterface $template, TemplateRenderingContextInterface $context): void;
}

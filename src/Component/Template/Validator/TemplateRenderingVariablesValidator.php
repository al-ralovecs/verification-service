<?php

declare(strict_types=1);

namespace Component\Template\Validator;

use Component\Template\Context\TemplateRenderingContextInterface;
use Component\Template\Exception\RenderTemplateValidationFailedException;
use Component\Template\Model\TemplateInterface;

final class TemplateRenderingVariablesValidator implements TemplateRenderingValidatorInterface
{
    public function __invoke(TemplateInterface $template, TemplateRenderingContextInterface $context): void
    {
        array_map(
            static function (string $variable) use ($context): void {
                if (array_key_exists($variable, $context->variables())) {
                    return;
                }

                throw RenderTemplateValidationFailedException::missingVariable($variable);
            },
            $template->content()->variables(),
        );
    }
}

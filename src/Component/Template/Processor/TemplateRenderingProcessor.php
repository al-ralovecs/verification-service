<?php

declare(strict_types=1);

namespace Component\Template\Processor;

use Component\Template\Context\TemplateRenderingContextInterface;
use Component\Template\Exception\RenderTemplateValidationFailedException;
use Component\Template\Factory\ResponseHeaderFactory;
use Component\Template\Model\TemplateInterface;
use Component\Template\Response\TemplateRenderingResponse;
use Component\Template\Response\TemplateRenderingResponseInterface;

final class TemplateRenderingProcessor implements TemplateRenderingProcessorInterface
{
    public function __invoke(TemplateInterface $template, TemplateRenderingContextInterface $context): TemplateRenderingResponseInterface
    {
        $content = $template->content()->body();

        if (false !== preg_match_all('/{{ (?<param>[^}]*) }}/', $content, $matches)) {
            foreach ($matches['param'] as $param) {
                if (null === $paramValue = $context->variables()[$param]) { // @phpstan-ignore-line
                    throw RenderTemplateValidationFailedException::missingValue($param);
                }

                $content = str_replace(sprintf('{{ %s }}', $param), (string) $paramValue, $content);
            }
        }

        return new TemplateRenderingResponse(
            ResponseHeaderFactory::contentTypeHeader($template->content()->contentType()),
            $content,
        );
    }
}

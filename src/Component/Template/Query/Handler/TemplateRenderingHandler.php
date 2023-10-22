<?php

declare(strict_types=1);

namespace Component\Template\Query\Handler;

use Component\Template\Context\TemplateRenderingContext;
use Component\Template\Operator\TemplateFetcherInterface;
use Component\Template\Processor\TemplateRenderingProcessorInterface;
use Component\Template\Query\TemplateRenderingQueryInterface;
use Component\Template\Response\TemplateRenderingResponseInterface;

final readonly class TemplateRenderingHandler implements TemplateRenderingHandlerInterface
{
    public function __construct(
        private TemplateFetcherInterface $templateFetcher,
        private TemplateRenderingProcessorInterface $templateRenderingProcessor,
    ) {
    }

    public function __invoke(TemplateRenderingQueryInterface $query): TemplateRenderingResponseInterface
    {
        $template = $this->templateFetcher->getBySlug($query->slug());
        $context = new TemplateRenderingContext($query->variables());

        return ($this->templateRenderingProcessor)($template, $context);
    }
}

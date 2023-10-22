<?php

declare(strict_types=1);

namespace Component\Template\Processor;

use Component\Template\Context\TemplateRenderingContextInterface;
use Component\Template\Model\TemplateInterface;
use Component\Template\Response\TemplateRenderingResponseInterface;

interface TemplateRenderingProcessorInterface
{
    public function __invoke(TemplateInterface $template, TemplateRenderingContextInterface $context): TemplateRenderingResponseInterface;
}

<?php

declare(strict_types=1);

namespace Component\Template\Query\Handler;

use Component\Template\Query\TemplateRenderingQueryInterface;
use Component\Template\Response\TemplateRenderingResponseInterface;

interface TemplateRenderingHandlerInterface
{
    public function __invoke(TemplateRenderingQueryInterface $query): TemplateRenderingResponseInterface;
}

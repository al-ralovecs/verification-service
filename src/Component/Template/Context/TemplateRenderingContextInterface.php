<?php

declare(strict_types=1);

namespace Component\Template\Context;

interface TemplateRenderingContextInterface
{
    /**
     * @return array<string, string|int>
     */
    public function variables(): array;
}

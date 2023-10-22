<?php

declare(strict_types=1);

namespace Component\Template\Query;

interface TemplateRenderingQueryInterface
{
    public function slug(): string;

    /**
     * @return array<string, string|int>
     */
    public function variables(): array;
}

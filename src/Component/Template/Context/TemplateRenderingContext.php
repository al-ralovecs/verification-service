<?php

declare(strict_types=1);

namespace Component\Template\Context;

final readonly class TemplateRenderingContext implements TemplateRenderingContextInterface
{
    /**
     * @param array<string, string|int> $variables
     */
    public function __construct(
        private array $variables,
    ) {
    }

    /**
     * @return array<string, string|int>
     */
    public function variables(): array
    {
        return $this->variables;
    }
}

<?php

declare(strict_types=1);

namespace Component\Template\Bridge\Api\Request;

use Component\Template\Query\TemplateRenderingQueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class RenderTemplateRequest implements TemplateRenderingQueryInterface
{
    /**
     * @param array<string, string|int> $variables
     */
    public function __construct(
        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ])]
        public string $slug,

        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('array'),
        ])]
        public array $variables,
    ) {
    }

    public function slug(): string
    {
        return $this->slug;
    }

    /**
     * @return array<string, string|int>
     */
    public function variables(): array
    {
        return $this->variables;
    }
}

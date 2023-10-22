<?php

declare(strict_types=1);

namespace Component\Template\Model;

use Component\Template\Enum\ContentType;

readonly class TemplateContent implements TemplateContentInterface
{
    public function __construct(
        private ContentType $contentType,
        private string $body,
        /** @var string[] $variables */
        private array $variables,
    ) {
    }

    public function contentType(): ContentType
    {
        return $this->contentType;
    }

    public function body(): string
    {
        return $this->body;
    }

    /**
     * @return string[]
     */
    public function variables(): array
    {
        return $this->variables;
    }
}

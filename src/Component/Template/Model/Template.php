<?php

declare(strict_types=1);

namespace Component\Template\Model;

use Component\Template\Enum\ContentType;

readonly class Template implements TemplateInterface
{
    private TemplateContentInterface $content;

    /**
     * @param string[] $contentVariables
     */
    public function __construct(
        private string $id,
        private string $slug,
        ContentType $contentType,
        string $contentBody,
        array $contentVariables,
    ) {
        $this->content = new TemplateContent(
            $contentType,
            $contentBody,
            $contentVariables,
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function content(): TemplateContentInterface
    {
        return $this->content;
    }
}

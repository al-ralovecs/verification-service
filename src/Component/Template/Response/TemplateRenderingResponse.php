<?php

declare(strict_types=1);

namespace Component\Template\Response;

final readonly class TemplateRenderingResponse implements TemplateRenderingResponseInterface
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        private array $headers,
        private string $content,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function headers(): array
    {
        return $this->headers;
    }

    public function content(): string
    {
        return $this->content;
    }
}

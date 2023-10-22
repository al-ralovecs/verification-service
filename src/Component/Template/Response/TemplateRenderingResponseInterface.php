<?php

declare(strict_types=1);

namespace Component\Template\Response;

interface TemplateRenderingResponseInterface
{
    /**
     * @return array<string, string>
     */
    public function headers(): array;

    public function content(): string;
}

<?php

declare(strict_types=1);

namespace Component\Template\Model;

use Component\Template\Enum\ContentType;

interface TemplateContentInterface
{
    public function contentType(): ContentType;

    public function body(): string;

    /** @return string[] */
    public function variables(): array;
}

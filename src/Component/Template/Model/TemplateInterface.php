<?php

declare(strict_types=1);

namespace Component\Template\Model;

interface TemplateInterface
{
    public function id(): string;

    public function slug(): string;

    public function content(): TemplateContentInterface;
}

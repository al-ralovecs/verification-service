<?php

declare(strict_types=1);

namespace Component\Template\Operator;

use Component\Template\Exception\TemplateDoesNotExistException;
use Component\Template\Model\TemplateInterface;

interface TemplateFetcherInterface
{
    /**
     * @throws TemplateDoesNotExistException
     */
    public function getBySlug(string $slug): TemplateInterface;
}

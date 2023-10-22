<?php

declare(strict_types=1);

namespace Component\Template\Bridge\Api\Responder;

use Component\Template\Query\Handler\TemplateRenderingHandlerInterface;
use Component\Template\Query\TemplateRenderingQueryInterface;
use Symfony\Component\HttpFoundation\Response;

final readonly class RenderTemplateActionResponder
{
    public function __construct(
        private TemplateRenderingHandlerInterface $templateRenderingHandler,
    ) {
    }

    public function __invoke(TemplateRenderingQueryInterface $query): Response
    {
        $response = ($this->templateRenderingHandler)($query);

        return new Response(
            $response->content(),
            Response::HTTP_OK,
            $response->headers(),
        );
    }
}

<?php

declare(strict_types=1);

namespace Application\Controller\Template;

use Component\Template\Bridge\Api\Request\RenderTemplateRequest;
use Component\Template\Bridge\Api\Responder\RenderTemplateActionResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/templates/render',
    name: 'app.api.template.render',
    methods: [Request::METHOD_POST],
)]
final readonly class RenderTemplateAction
{
    public function __construct(
        private RenderTemplateActionResponder $renderTemplateActionResponder,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload()] RenderTemplateRequest $request,
    ): Response {
        return ($this->renderTemplateActionResponder)($request);
    }
}

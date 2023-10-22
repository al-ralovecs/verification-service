<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Application\List;

use Component\Gateway\Operator\ResponseInterface;
use Component\Gotify\Dto\ApplicationDtoCollection;

final readonly class Response implements ResponseInterface
{
    public function __construct(
        private ?ApplicationDtoCollection $applications,
    ) {
    }

    public function applications(): ?ApplicationDtoCollection
    {
        return $this->applications;
    }
}

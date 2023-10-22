<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Application\Create;

use Component\Gateway\Operator\ResponseInterface;
use Component\Gotify\Dto\ApplicationDto;

final readonly class Response implements ResponseInterface
{
    public function __construct(
        private ApplicationDto $application,
    ) {
    }

    public function application(): ApplicationDto
    {
        return $this->application;
    }
}

<?php

declare(strict_types=1);

namespace Component\Gotify\Command;

interface AppTokenAwareSendMesageCommandInterface extends SendMessageCommandInterface
{
    public function token(): ?string;

    public function changeToken(string $token): void;
}

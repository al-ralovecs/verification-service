<?php

declare(strict_types=1);

namespace Component\Gotify\Command;

interface SendMessageCommandInterface
{
    public function recipient(): string;

    public function content(): string;
}

<?php

declare(strict_types=1);

namespace Component\Messenger\Model;

interface MessageToConnectionMapInterface
{
    /**
     * @return string[]
     */
    public function connections(string $messageClass): array;
}

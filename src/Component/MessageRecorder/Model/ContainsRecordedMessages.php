<?php

declare(strict_types=1);

namespace Component\MessageRecorder\Model;

interface ContainsRecordedMessages
{
    /**
     * @return object[]
     */
    public function recordedMessages(): iterable;

    public function eraseMessages(): void;
}

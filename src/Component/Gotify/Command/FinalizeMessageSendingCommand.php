<?php

declare(strict_types=1);

namespace Component\Gotify\Command;

final class FinalizeMessageSendingCommand extends AbstractAppTokenAwareSendMessageCommand
{
    public function __construct(string $recipient, string $content, string $token)
    {
        parent::__construct($recipient, $content);
        $this->changeToken($token);
    }
}

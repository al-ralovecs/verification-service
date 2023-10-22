<?php

declare(strict_types=1);

namespace Component\Gotify\Command;

abstract class AbstractAppTokenAwareSendMessageCommand extends AbstractSendMessageCommand implements AppTokenAwareSendMesageCommandInterface
{
    private ?string $token = null;

    public function token(): ?string
    {
        return $this->token;
    }

    public function changeToken(string $token): void
    {
        $this->token = $token;
    }

    public function jsonSerialize(): array
    {
        return array_filter(array_merge([
            'token' => $this->token,
        ], parent::jsonSerialize()));
    }

    /**
     * @param array<string, string> $data
     */
    public function __unserialize(array $data): void
    {
        parent::__unserialize($data);

        $this->token = (null === ($data['token'] ?? null)) ? null : $data['token'];
    }
}

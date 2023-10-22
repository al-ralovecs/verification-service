<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Message;

use Component\Http\Request\Body\JsonRequestBody;

final readonly class RequestBody extends JsonRequestBody
{
    public function __construct(
        private string $message,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'message' => $this->message,
        ];
    }
}

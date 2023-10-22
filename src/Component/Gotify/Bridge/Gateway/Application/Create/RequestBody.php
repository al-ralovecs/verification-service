<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Application\Create;

use Component\Http\Request\Body\JsonRequestBody;

final readonly class RequestBody extends JsonRequestBody
{
    public function __construct(
        private string $name,
        private string $description,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}

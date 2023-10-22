<?php

declare(strict_types=1);

namespace Component\Notification\Bridge\Gateway\Template;

use Component\Http\Request\Body\JsonRequestBody;

final readonly class RequestBody extends JsonRequestBody
{
    public function __construct(
        private string $slug,
        private string $code,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'slug' => $this->slug,
            'variables' => [
                'code' => $this->code,
            ],
        ];
    }
}

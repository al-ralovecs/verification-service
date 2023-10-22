<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Recipient\List;

use Component\Gateway\Operator\ResponseInterface;
use Component\Gateway\Response\Transformer\AbstractClientJsonResponseTransformer;
use Component\Gotify\Dto\RecipientDto;
use Component\Gotify\Dto\RecipientDtoCollection;

final readonly class ResponseTransformer extends AbstractClientJsonResponseTransformer
{
    protected function parseResponseData(mixed $data): ResponseInterface
    {
        assert(is_array($data));

        if (0 === count($data)) {
            return new Response(null);
        }

        /** @var array<string, RecipientDto> $recipients */
        $recipients = array_reduce(
            $data,
            static function (array $result, array $item): array {
                $recipient = new RecipientDto($item);
                $result[$recipient->name()] = $recipient;

                return $result;
            },
            [],
        );

        return new Response(RecipientDtoCollection::create($recipients));
    }
}

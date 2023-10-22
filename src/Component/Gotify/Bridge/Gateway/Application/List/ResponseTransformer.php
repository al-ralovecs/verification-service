<?php

declare(strict_types=1);

namespace Component\Gotify\Bridge\Gateway\Application\List;

use Component\Gateway\Operator\ResponseInterface;
use Component\Gateway\Response\Transformer\AbstractClientJsonResponseTransformer;
use Component\Gotify\Dto\ApplicationDto;
use Component\Gotify\Dto\ApplicationDtoCollection;

final readonly class ResponseTransformer extends AbstractClientJsonResponseTransformer
{
    protected function parseResponseData(mixed $data): ResponseInterface
    {
        assert(is_array($data));

        if (0 === count($data)) {
            return new Response(null);
        }

        /** @var array<string, ApplicationDto> $apps */
        $apps = array_reduce(
            $data,
            static function (array $result, array $item): array {
                $app = new ApplicationDto($item);
                $result[$app->name()] = $app;

                return $result;
            },
            [],
        );

        return new Response(ApplicationDtoCollection::create($apps));
    }
}

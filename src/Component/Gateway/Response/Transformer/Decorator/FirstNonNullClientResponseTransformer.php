<?php

declare(strict_types=1);

namespace Component\Gateway\Response\Transformer\Decorator;

use Component\Gateway\Operator\ResponseInterface;
use Component\Gateway\Response\Transformer\ClientResponseTransformerInterface;
use Psr\Http\Message\ResponseInterface as ClientResponse;
use SN\Collection\Enum\Priority;
use SN\Collection\Model\PrioritizedCollection;

final readonly class FirstNonNullClientResponseTransformer implements ClientResponseTransformerInterface
{
    /**
     * @var PrioritizedCollection<ClientResponseTransformerInterface>
     */
    private PrioritizedCollection $collection;

    public function __construct()
    {
        $this->collection = new PrioritizedCollection();
    }

    public function add(ClientResponseTransformerInterface $transformer, int $priority = Priority::NORMAL): void
    {
        $this->collection->add($transformer, $priority);
    }

    public function transform(ClientResponse $response): ?ResponseInterface
    {
        /* @phpstan-ignore-next-line */
        return $this->collection->firstNonNullResult(
            static fn (ClientResponseTransformerInterface $transformer): ?ResponseInterface => $transformer->transform($response),
        );
    }
}

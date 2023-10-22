<?php

declare(strict_types=1);

namespace Component\Notification\Fetcher;

use Component\Gateway\Operator\GatewayExceptionInterface;
use Component\Notification\Bridge\Gateway\Template\Gateway;
use Component\Notification\Bridge\Gateway\Template\Request;
use Component\Notification\Enum\Channel;
use Component\Notification\Transformer\ChannelToTemplateSlugTransformer;

final readonly class NotificationContentFetcher implements NotificationContentFetcherInterface
{
    public function __construct(
        private Gateway $gateway,
    ) {
    }

    /**
     * @throws GatewayExceptionInterface
     */
    public function get(Channel $channel, string $code): string
    {
        $slug = ChannelToTemplateSlugTransformer::transform($channel);
        $request = new Request($slug, $code);

        return $this->gateway->request($request)->body();
    }
}

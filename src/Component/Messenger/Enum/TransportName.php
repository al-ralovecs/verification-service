<?php

declare(strict_types=1);

namespace Component\Messenger\Enum;

use MabeEnum\Enum;

/**
 * @method string getValue()
 */
final class TransportName extends Enum
{
    public const VERIFICATION_CREATED = 'verification_created';
    public const VERIFICATION_CONFIRMED = 'verification_confirmed';
    public const VERIFICATION_CONFIRMATION_FAILED = 'verification_confirmation_failed';

    public const NOTIFICATION_DISPATCH = 'notification_dispatch';

    public const NOTIFICATION_CREATED = 'notification_created';
    public const NOTIFICATION_DISPATCHED = 'notification_dispatched';

    public const SEND_EMAIL_MESSAGE = 'send_email_message';

    public const SEND_MOBILE_MESSAGE = 'send_mobile_message';
}

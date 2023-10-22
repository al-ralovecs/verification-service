<?php

declare(strict_types=1);

namespace Component\Messenger\Enum;

use MabeEnum\Enum;

/**
 * @method string getValue()
 */
final class QueueName extends Enum
{
    public const VERIFICATION_CREATED = 'verification_created';
    public const VERIFICATION_CONFIRMED = 'verification_confirmed';
    public const VERIFICATION_CONFIRMATION_FAILED = 'verification_confirmation_failed';

    public const NOTIFICATION_DISPATCH = 'notification_dispatch';

    public const NOTIFICATION_CREATED = 'notification_created';
    public const NOTIFICATION_DISPATCHED = 'notification_dispatched';

    public const SEND_EMAIL_MESSAGE = 'send_email_message';

    public const SEND_MOBILE_MESSAGE = 'send_mobile_message';

    public static function byTransportName(TransportName $transportName): self
    {
        // @phpstan-ignore-next-line
        return match ($transportName->getValue()) {
            TransportName::VERIFICATION_CREATED => self::byValue(self::VERIFICATION_CREATED),
            TransportName::VERIFICATION_CONFIRMED => self::byValue(self::VERIFICATION_CONFIRMED),
            TransportName::VERIFICATION_CONFIRMATION_FAILED => self::byValue(self::VERIFICATION_CONFIRMATION_FAILED),
            TransportName::NOTIFICATION_DISPATCH => self::byValue(self::NOTIFICATION_DISPATCH),
            TransportName::NOTIFICATION_CREATED => self::byValue(self::NOTIFICATION_CREATED),
            TransportName::NOTIFICATION_DISPATCHED => self::byValue(self::NOTIFICATION_DISPATCHED),
            TransportName::SEND_EMAIL_MESSAGE => self::byValue(self::SEND_EMAIL_MESSAGE),
            TransportName::SEND_MOBILE_MESSAGE => self::byValue(self::SEND_MOBILE_MESSAGE),
        };
    }

    public function toTransportName(): TransportName
    {
        // @phpstan-ignore-next-line
        return match ($this->getValue()) {
            self::VERIFICATION_CREATED => TransportName::byValue(TransportName::VERIFICATION_CREATED),
            self::VERIFICATION_CONFIRMED => TransportName::byValue(TransportName::VERIFICATION_CONFIRMED),
            self::VERIFICATION_CONFIRMATION_FAILED => TransportName::byValue(TransportName::VERIFICATION_CONFIRMATION_FAILED),
            self::NOTIFICATION_DISPATCH => TransportName::byValue(TransportName::NOTIFICATION_DISPATCH),
            self::NOTIFICATION_CREATED => TransportName::byValue(TransportName::NOTIFICATION_CREATED),
            self::NOTIFICATION_DISPATCHED => TransportName::byValue(TransportName::NOTIFICATION_DISPATCHED),
            self::SEND_EMAIL_MESSAGE => TransportName::byValue(TransportName::SEND_EMAIL_MESSAGE),
            self::SEND_MOBILE_MESSAGE => TransportName::byValue(TransportName::SEND_MOBILE_MESSAGE),
        };
    }
}

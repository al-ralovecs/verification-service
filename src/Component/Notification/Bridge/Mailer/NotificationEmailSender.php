<?php

declare(strict_types=1);

namespace Component\Notification\Bridge\Mailer;

use Component\Notification\Sender\NotificationEmailSenderInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final readonly class NotificationEmailSender implements NotificationEmailSenderInterface
{
    public function __construct(
        private MailerInterface $mailer,
        private string $senderEmail,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(string $recipient, string $content): void
    {
        $email = (new Email())
            ->from($this->senderEmail)
            ->to($recipient)
            ->html($content);

        $this->mailer->send($email);
    }
}

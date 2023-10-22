<?php

declare(strict_types=1);

namespace Component\Verification\Model;

use Component\MessageRecorder\Model\ContainsRecordedMessages;
use Component\MessageRecorder\Model\PrivateMessageRecorderCapabilities;
use Component\Verification\Command\CreateVerificationCommand;
use Component\Verification\Event\VerificationConfirmationFailedEvent;
use Component\Verification\Event\VerificationConfirmedEvent;
use Component\Verification\Event\VerificationCreatedEvent;
use Component\Verification\Exception\CannotConfirmVerificationException;
use Component\Verification\Operator\VerificationSubjectInterface;
use DateTimeImmutable;

class Verification implements VerificationInterface, ContainsRecordedMessages
{
    use PrivateMessageRecorderCapabilities;

    private bool $confirmed;
    private int $attempts;
    private DateTimeImmutable $updatedAt;

    /**
     * @param numeric-string $code
     */
    public function __construct(
        private readonly string $id,
        private readonly VerificationSubjectInterface $subject,
        private readonly string $code,
        private readonly VerificationUserInfoInterface $userInfo,
        private readonly DateTimeImmutable $createdAt,
    ) {
        $this->confirmed = false;
        $this->attempts = 0;
        $this->updatedAt = $this->createdAt;

        $event = new VerificationCreatedEvent(
            $this->id,
            $this->code,
            $this->subject,
            $this->createdAt,
        );
        $this->record($event);
    }

    /**
     * @param numeric-string $code
     */
    public static function create(CreateVerificationCommand $command, string $code): self
    {
        return new self(
            $command->id(),
            $command->subject(),
            $code,
            $command->userInfo(),
            $command->createdAt(),
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function subject(): VerificationSubjectInterface
    {
        return $this->subject;
    }

    public function confirmed(): bool
    {
        return $this->confirmed;
    }

    public function confirm(): void
    {
        if (true === $this->confirmed) {
            throw CannotConfirmVerificationException::alreadyConfirmed($this);
        }

        $this->confirmed = true;

        $updatedAt = new DateTimeImmutable();
        $this->updatedAt = $updatedAt;

        $event = new VerificationConfirmedEvent(
            $this->id,
            $this->code,
            $this->subject,
            $updatedAt,
        );
        $this->record($event);
    }

    public function code(): string
    {
        return $this->code;
    }

    public function userInfo(): VerificationUserInfoInterface
    {
        return $this->userInfo;
    }

    public function attempts(): int
    {
        return $this->attempts;
    }

    public function failed(string $code): void
    {
        ++$this->attempts;

        $updatedAt = new DateTimeImmutable();
        $this->updatedAt = $updatedAt;

        $this->record(new VerificationConfirmationFailedEvent(
            $this->id(),
            $code,
            $this->subject(),
            $updatedAt,
        ));
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}

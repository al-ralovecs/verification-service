<?php

declare(strict_types=1);

namespace Component\Verification\Event;

use Component\Verification\Model\VerificationSubject;
use Component\Verification\Operator\Enum\VerificationSubjectType;
use Component\Verification\Operator\VerificationSubjectInterface;
use DateTimeImmutable;
use Exception;
use JsonSerializable;

abstract readonly class AbstractVerificationEvent implements JsonSerializable
{
    public function __construct(
        private string $id,
        private string $code,
        private VerificationSubjectInterface $subject,
        private DateTimeImmutable $occurredOn,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function subject(): VerificationSubjectInterface
    {
        return $this->subject;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        /** @var array<string, string> $serialized */
        $serialized = array_merge([
            'id' => $this->id,
            'code' => $this->code,
            'occurred_on' => $this->occurredOn->format('Y-m-d H:i:s'),
        ], $this->subject()->jsonSerialize());

        return $serialized;
    }

    /**
     * @return array<string, string>
     */
    public function __serialize(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * @param array<string, string> $data
     *
     * @throws Exception
     */
    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->code = $data['code'];
        $this->subject = new VerificationSubject(
            $data['identity'],
            VerificationSubjectType::from($data['type']),
        );
        $this->occurredOn = new DateTimeImmutable($data['occurred_on']);
    }
}

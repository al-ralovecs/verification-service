<?php

declare(strict_types=1);

namespace App\Tests\Functional\Verification\Bridge\Api\Responder;

use Component\Common\Operator\StatusCodeAwareExceptionInterface;
use Component\Verification\Bridge\Api\Request\ConfirmVerificationRequest;
use Component\Verification\Bridge\Api\Request\VerificationRequestContext;
use Component\Verification\Bridge\Api\Responder\ConfirmVerificationActionResponder;
use Component\Verification\Command\CreateVerificationCommand;
use Component\Verification\Command\Handler\ConfirmVerificationHandler;
use Component\Verification\Exception\VerificationExpiredException;
use Component\Verification\Exception\VerificationFailedException;
use Component\Verification\Exception\VerificationNotPermittedException;
use Component\Verification\Model\Verification;
use Component\Verification\Model\VerificationSubject;
use Component\Verification\Model\VerificationUserInfo;
use Component\Verification\Operator\Enum\VerificationSubjectType;
use Component\Verification\Operator\VerificationFetcherInterface;
use Component\Verification\Operator\VerificationPersisterInterface;
use Component\Verification\Processor\Confirm\ConfirmingVerificationProcessor;
use Component\Verification\Processor\Confirm\Decorator\ChainVerificationProcessor;
use Component\Verification\Processor\Confirm\Decorator\ExceptionCatchingVerificationProcessor;
use Component\Verification\Processor\Confirm\PersistingVerificationProcessor;
use Component\Verification\Processor\Confirm\VerificationValidatingProcessor;
use Component\Verification\Validator\Confirm\ConfirmVerificationAttemptsValidator;
use Component\Verification\Validator\Confirm\ConfirmVerificationCodeValidator;
use Component\Verification\Validator\Confirm\ConfirmVerificationExpiredValidator;
use Component\Verification\Validator\Confirm\ConfirmVerificationPermittedValidator;
use Component\Verification\Validator\Confirm\Decorator\ChainConfirmVerificationValidator;
use DateInterval;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SN\Collection\Enum\Priority;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\Uid\Uuid;

final class ConfirmVerificationActionResponderTest extends TestCase
{
    private const USER_AGENT = 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/103.0.5060.134 Safari/537.36';

    private ConfirmVerificationActionResponder $responder;
    /** @var (VerificationFetcherInterface&MockObject) */
    private VerificationFetcherInterface $verificationFetcher;
    /** @var (VerificationPersisterInterface&MockObject) */
    private VerificationPersisterInterface $verificationPersister;

    protected function setUp(): void
    {
        $validator = new ChainConfirmVerificationValidator();
        $validator->add(new ConfirmVerificationExpiredValidator(new DateInterval('PT5M')));
        $validator->add(new ConfirmVerificationAttemptsValidator(5));
        $validator->add(new ConfirmVerificationPermittedValidator());
        $validator->add(new ConfirmVerificationCodeValidator());

        $confirmer = new ChainVerificationProcessor();
        $confirmer->add(new ExceptionCatchingVerificationProcessor(
            new VerificationValidatingProcessor($validator),
            $this->verificationPersister = $this->createMock(VerificationPersisterInterface::class),
        ), Priority::HIGH);
        $confirmer->add(new ConfirmingVerificationProcessor(
        ), Priority::NORMAL);
        $confirmer->add(new PersistingVerificationProcessor(
            $this->verificationPersister,
        ), Priority::LOW);

        $this->responder = new ConfirmVerificationActionResponder(
            new ConfirmVerificationHandler(
                $this->verificationFetcher = $this->createMock(VerificationFetcherInterface::class),
                $confirmer,
            ),
        );
    }

    /**
     * @test
     */
    public function it_confirms_verification_successfully(): void
    {
        $verificationId = (string) Uuid::v7();
        $request = new ConfirmVerificationRequest($code = '1234');
        $context = new VerificationRequestContext(
            $ip = '192.168.0.1',
            new HeaderBag(['User-Agent' => self::USER_AGENT]),
        );

        $verification = Verification::create(new CreateVerificationCommand(
            $verificationId,
            new VerificationSubject(
                'john.doe@xyz.abc',
                VerificationSubjectType::EMAIL,
            ),
            new VerificationUserInfo(
                $ip,
                self::USER_AGENT,
            ),
            new DateTimeImmutable(),
        ), $code);

        $this->verificationFetcher
            ->expects(self::once())
            ->method('getById')
            ->willReturn($verification);

        $this->verificationPersister
            ->expects(self::once())
            ->method('save');

        $response = ($this->responder)($verificationId, $request, $context);
        self::assertSame(204, $response->getStatusCode());
    }

    /**
     * @test
     *
     * @dataProvider confirmationData
     *
     * @param numeric-string $confirmationCode
     * @param numeric-string $code
     */
    public function it_fails_on_verification_confirmation(
        string $verificationId,
        string $confirmationCode,
        string $code,
        string $confirmationIp,
        string $ip,
        DateTimeImmutable $verificationCreatedAt,
        Exception $exception,
        int $statusCode,
    ): void {
        $request = new ConfirmVerificationRequest($confirmationCode);
        $context = new VerificationRequestContext(
            $confirmationIp,
            new HeaderBag(['User-Agent' => self::USER_AGENT]),
        );

        $verification = Verification::create(new CreateVerificationCommand(
            $verificationId,
            new VerificationSubject(
                'john.doe@xyz.abc',
                VerificationSubjectType::EMAIL,
            ),
            new VerificationUserInfo(
                $ip,
                self::USER_AGENT,
            ),
            $verificationCreatedAt,
        ), $code);

        $this->verificationFetcher
            ->expects(self::once())
            ->method('getById')
            ->willReturn($verification);

        $this->verificationPersister
            ->expects(self::once())
            ->method('save');

        $this->expectExceptionObject($exception);
        ($this->responder)($verificationId, $request, $context);
        self::assertTrue($exception instanceof StatusCodeAwareExceptionInterface);
        self::assertSame($statusCode, $exception->statusCode());
    }

    /**
     * @return iterable<array<int, Exception|DateTimeImmutable|string|int>>
     */
    public static function confirmationData(): iterable
    {
        /** @var Exception $exception */
        $exception = new VerificationFailedException($id = (string) Uuid::v7());
        yield [
            $id,
            '4321',
            '1234',
            '192.168.0.1',
            '192.168.0.1',
            new DateTimeImmutable(),
            $exception,
            422,
        ];

        /** @var Exception $exception */
        $exception = new VerificationNotPermittedException($id = (string) Uuid::v7());
        yield [
            $id,
            '1234',
            '1234',
            '192.168.0.1',
            '192.168.0.2',
            new DateTimeImmutable(),
            $exception,
            403,
        ];

        /** @var Exception $exception */
        $exception = new VerificationExpiredException($id = (string) Uuid::v7());
        yield [
            $id,
            '1234',
            '1234',
            '192.168.0.1',
            '192.168.0.1',
            (new DateTimeImmutable())->sub(new DateInterval('PT6M')),
            $exception,
            410,
        ];
    }
}

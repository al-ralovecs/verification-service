<?php

declare(strict_types=1);

namespace App\Tests\Functional\Verification\Bridge\Api\Responder;

use Component\Verification\Bridge\Api\Request\CreateVerificationRequest;
use Component\Verification\Bridge\Api\Request\VerificationRequestContext;
use Component\Verification\Bridge\Api\Responder\CreateVerificationActionResponder;
use Component\Verification\Checker\DuplicateVerificationCheckerInterface;
use Component\Verification\Command\Handler\CreateVerificationHandler;
use Component\Verification\Generator\VerificationCodeGenerator;
use Component\Verification\Operator\VerificationPersisterInterface;
use Component\Verification\Processor\Create\Decorator\ChainVerificationProcessor;
use Component\Verification\Processor\Create\PersistingVerificationProcessor;
use Component\Verification\Processor\Create\VerificationValidatingProcessor;
use Component\Verification\Validator\Create\CreateVerificationDuplicateValidator;
use Component\Verification\Validator\Create\Decorator\ChainCreateVerificationValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\Uid\Uuid;

final class CreateVerificationActionResponderTest extends TestCase
{
    private const USER_AGENT = 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/103.0.5060.134 Safari/537.36';

    private CreateVerificationActionResponder $responder;
    /** @var (DuplicateVerificationCheckerInterface&MockObject) */
    private DuplicateVerificationCheckerInterface $duplicateVerificationChecker;
    /** @var (VerificationPersisterInterface&MockObject) */
    private VerificationPersisterInterface $verificationPersister;

    protected function setUp(): void
    {
        $validator = new ChainCreateVerificationValidator();
        $validator->add(new CreateVerificationDuplicateValidator(
            $this->duplicateVerificationChecker = $this->createMock(DuplicateVerificationCheckerInterface::class),
        ));

        $processor = new ChainVerificationProcessor();
        $processor->add(new VerificationValidatingProcessor($validator));
        $processor->add(new PersistingVerificationProcessor(
            $this->verificationPersister = $this->createMock(VerificationPersisterInterface::class),
        ));

        $this->responder = new CreateVerificationActionResponder(
            new CreateVerificationHandler(
                new VerificationCodeGenerator(),
                $processor,
            ),
        );
    }

    /**
     * @test
     */
    public function it_creates_verification_successfully(): void
    {
        $request = new CreateVerificationRequest([
            'type' => 'email_confirmation',
            'identity' => 'john.doe@abc.xyz',
        ]);
        $context = new VerificationRequestContext(
            '192.168.0.1',
            new HeaderBag(['User-Agent' => self::USER_AGENT]),
        );

        $this->duplicateVerificationChecker
            ->expects(self::once())
            ->method('duplicateExists')
            ->willReturn(false);
        $this->verificationPersister
            ->expects(self::once())
            ->method('save');

        $response = ($this->responder)($request, $context);
        self::assertSame(201, $response->getStatusCode());
        self::assertTrue(is_string($response->getContent()));

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('id', $content);
        self::assertTrue(Uuid::isValid($content['id']));
    }
}

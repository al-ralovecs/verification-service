<?php

declare(strict_types=1);

namespace App\Tests\Functional\Template\Bridge\Api\Responder;

use App\Tests\DataFixtures\Template\EmailTemplateFixture;
use App\Tests\DataFixtures\Template\SmsTemplateFixture;
use Component\Template\Bridge\Api\Request\RenderTemplateRequest;
use Component\Template\Bridge\Api\Responder\RenderTemplateActionResponder;
use Component\Template\Exception\RenderTemplateValidationFailedException;
use Component\Template\Exception\TemplateDoesNotExistException;
use Component\Template\Model\Template;
use Component\Template\Model\TemplateInterface;
use Component\Template\Operator\TemplateFetcherInterface;
use Component\Template\Processor\Decorator\ValidatingTemplateRenderingProcessor;
use Component\Template\Processor\TemplateRenderingProcessor;
use Component\Template\Query\Handler\TemplateRenderingHandler;
use Component\Template\Validator\Decorator\ChainTemplateRenderingValidator;
use Component\Template\Validator\TemplateRenderingVariablesValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class RenderTemplateActionResponderTest extends TestCase
{
    private RenderTemplateActionResponder $responder;
    /** @var (TemplateFetcherInterface&MockObject) */
    private TemplateFetcherInterface $templateFetcher;

    protected function setUp(): void
    {
        $validator = new ChainTemplateRenderingValidator();
        $validator->add(new TemplateRenderingVariablesValidator());

        $this->responder = new RenderTemplateActionResponder(
            new TemplateRenderingHandler(
                $this->templateFetcher = $this->createMock(TemplateFetcherInterface::class),
                new ValidatingTemplateRenderingProcessor(
                    $validator,
                    new TemplateRenderingProcessor(),
                ),
            ),
        );
    }

    /**
     * @test
     */
    public function it_renders_mobile_template(): void
    {
        $slug = 'mobile-verification';
        $request = new RenderTemplateRequest(
            $slug,
            ['code' => '1234'],
        );

        $this->templateFetcher
            ->expects(self::once())
            ->method('getBySlug')
            ->willReturn($this->template($slug));

        $response = ($this->responder)($request);

        self::assertSame('Your verification code is 1234.', $response->getContent());
        self::assertSame('text/plain; charset=UTF-8', $response->headers->get('Content-Type'));
    }

    /**
     * @test
     */
    public function it_renders_email_template(): void
    {
        $slug = 'email-verification';
        $request = new RenderTemplateRequest(
            $slug,
            ['code' => '1234'],
        );

        $this->templateFetcher
            ->expects(self::once())
            ->method('getBySlug')
            ->willReturn($this->template($slug));

        $response = ($this->responder)($request);

        self::assertSame(<<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Email verification</title>
    <style>
        .content {
            margin: auto;
            width: 600px;
        }
    </style>
</head>
<body>
    <div class="content">
        <p>Your verification code is 1234.</p>
    </div>
</body>
</html>
HTML, $response->getContent());
        self::assertSame('text/html; charset=UTF-8', $response->headers->get('Content-Type'));
    }

    /**
     * @test
     */
    public function it_fails_with_template_not_found_exception(): void
    {
        $slug = 'whatsapp-verification';
        $request = new RenderTemplateRequest(
            $slug,
            ['code' => '1234'],
        );

        $this->templateFetcher
            ->expects(self::once())
            ->method('getBySlug')
            ->willThrowException($exception = TemplateDoesNotExistException::missingSlug($slug));

        $this->expectExceptionObject($exception);
        ($this->responder)($request);
    }

    /**
     * @test
     */
    public function it_fails_with_missing_variable_exception(): void
    {
        $slug = 'email-verification';
        $request = new RenderTemplateRequest(
            $slug,
            [],
        );

        $this->templateFetcher
            ->expects(self::once())
            ->method('getBySlug')
            ->willReturn($this->template($slug));

        $this->expectExceptionObject(RenderTemplateValidationFailedException::missingVariable('code'));
        ($this->responder)($request);
    }

    /**
     * @test
     */
    public function it_fails_with_missing_variable_value_exception(): void
    {
        $slug = 'email-verification';
        $request = new RenderTemplateRequest(
            $slug,
            ['code' => null], // @phpstan-ignore-line
        );

        $this->templateFetcher
            ->expects(self::once())
            ->method('getBySlug')
            ->willReturn($this->template($slug));

        $this->expectExceptionObject(RenderTemplateValidationFailedException::missingValue('code'));
        ($this->responder)($request);
    }

    protected function template(string $slug): TemplateInterface
    {
        $templates = [
            'mobile-verification' => new Template(
                SmsTemplateFixture::TEMPLATE_SMS_ID,
                SmsTemplateFixture::TEMPLATE_SMS_SLUG,
                SmsTemplateFixture::TEMPLATE_SMS_CONTENT_TYPE,
                SmsTemplateFixture::TEMPLATE_SMS_BODY,
                SmsTemplateFixture::TEMPLATE_SMS_VARIABLES,
            ),
            'email-verification' => new Template(
                EmailTemplateFixture::TEMPLATE_EMAIL_ID,
                EmailTemplateFixture::TEMPLATE_EMAIL_SLUG,
                EmailTemplateFixture::TEMPLATE_EMAIL_CONTENT_TYPE,
                EmailTemplateFixture::TEMPLATE_EMAIL_BODY,
                EmailTemplateFixture::TEMPLATE_EMAIL_VARIABLES,
            ),
        ];

        return $templates[$slug];
    }
}

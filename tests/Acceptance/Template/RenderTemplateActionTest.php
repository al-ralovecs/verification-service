<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Template;

use App\Tests\DataFixtures\Template\EmailTemplateFixture;
use App\Tests\DataFixtures\Template\SmsTemplateFixture;
use SN\TestFixturesBundle\FixturesTrait;
use SN\TestLib\Rest\RestTestTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RenderTemplateActionTest extends WebTestCase
{
    use FixturesTrait,
        RestTestTrait;

    private const TEMPLATE_RENDER_URL = '/templates/render';

    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->changeRestClient($this->client = self::createClient());

        $this->loadFixtures(static::getContainer());
    }

    /**
     * @test
     */
    public function it_renders_mobile_template(): void
    {
        $this->loadFixtures(static::getContainer(), [
            SmsTemplateFixture::class,
        ]);

        $response = $this->postRaw(self::TEMPLATE_RENDER_URL, 200, ['slug' => SmsTemplateFixture::TEMPLATE_SMS_SLUG, 'variables' => ['code' => '1234']]);

        self::assertSame('Your verification code is 1234.', $response);
        self::assertSame('text/plain; charset=UTF-8', $this->client->getResponse()->headers->get('Content-Type'));
    }

    /**
     * @test
     */
    public function it_renders_email_template(): void
    {
        $this->loadFixtures(static::getContainer(), [
            EmailTemplateFixture::class,
        ]);

        $response = $this->postRaw(self::TEMPLATE_RENDER_URL, 200, ['slug' => EmailTemplateFixture::TEMPLATE_EMAIL_SLUG, 'variables' => ['code' => '1234']]);

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
HTML, $response);
        self::assertSame('text/html; charset=UTF-8', $this->client->getResponse()->headers->get('Content-Type'));
    }

    /**
     * @test
     */
    public function it_fails_with_malformed_json(): void
    {
        $this->client->request(
            'POST',
            self::TEMPLATE_RENDER_URL,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"slug": "mobile-verification",}',
        );

        self::assertSame(400, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function it_fails_on_missing_request_parameter_slug(): void
    {
        $this->postRaw(self::TEMPLATE_RENDER_URL, 422, []);
    }

    /**
     * @test
     */
    public function it_fails_on_missing_request_parameter_variables(): void
    {
        $this->postRaw(self::TEMPLATE_RENDER_URL, 422, ['slug' => 'lorem-ipsum']);
    }

    /**
     * @test
     */
    public function it_fails_on_non_existing_template(): void
    {
        $slug = 'lorem-ipsum';
        $this->postRaw(self::TEMPLATE_RENDER_URL, 404, ['slug' => $slug, 'variables' => ['lorem' => 'ipsum']]);
    }

    /**
     * @test
     */
    public function it_fails_on_missing_variable_code(): void
    {
        $this->loadFixtures(static::getContainer(), [
            EmailTemplateFixture::class,
        ]);

        $this->postRaw(self::TEMPLATE_RENDER_URL, 422, ['slug' => EmailTemplateFixture::TEMPLATE_EMAIL_SLUG, 'variables' => ['lorem' => 'ipsum']]);
    }

    /**
     * @test
     */
    public function it_fails_on_empty_variable_code(): void
    {
        $this->loadFixtures(static::getContainer(), [
            EmailTemplateFixture::class,
        ]);

        $response = $this->postRaw(self::TEMPLATE_RENDER_URL, 422, ['slug' => EmailTemplateFixture::TEMPLATE_EMAIL_SLUG, 'variables' => ['code' => null]]);
    }
}

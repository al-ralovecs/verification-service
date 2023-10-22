<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Verification;

use Component\Verification\Bridge\Doctrine\Repository\VerificationRepository;
use SN\TestFixturesBundle\FixturesTrait;
use SN\TestLib\Rest\RestTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;
use Symfony\Component\Uid\Uuid;

final class CreateVerificationActionTest extends WebTestCase
{
    use FixturesTrait,
        RestTestTrait;

    private const CREATE_VERIFICATION_URL = '/verifications';
    private const CONFIRM_VERIFICATION_URL = '/verifications/%s/confirm';

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->changeRestClient(self::createClient());

        $this->loadFixtures(static::getContainer());
    }

    /**
     * @test
     */
    public function it_creates_verification(): void
    {
        $this->loadFixtures(static::getContainer());

        $response = $this->post(
            self::CREATE_VERIFICATION_URL,
            201,
            [
                'subject' => [
                    'type' => 'email_confirmation',
                    'identity' => 'john.doe@abc.xyz',
                ],
            ],
        );

        self::assertArrayHasKey('id', $response);
        self::assertTrue(Uuid::isValid($response['id']));

        /** @var VerificationRepository $verificationRepository */
        $verificationRepository = static::getContainer()
            ->get(VerificationRepository::class);
        $verification = $verificationRepository->getById($response['id']);

        self::assertSame('Symfony BrowserKit', $verification->userInfo()->userAgent());

        /** @var InMemoryTransport $transport */
        $transport = static::getContainer()
            ->get('messenger.transport.verification_created');

        self::assertCount(1, $transport->get());
    }

    /**
     * @test
     */
    public function it_prevents_creating_duplicated_verifications(): void
    {
        $this->loadFixtures(static::getContainer());

        $content = [
            'subject' => [
                'type' => 'email_confirmation',
                'identity' => 'john.doe@abc.xyz',
            ],
        ];

        $this->post(self::CREATE_VERIFICATION_URL, 201, $content);

        /** @var InMemoryTransport $transport */
        $transport = static::getContainer()
            ->get('messenger.transport.verification_created');

        self::assertCount(1, $transport->get());

        $this->postRaw(self::CREATE_VERIFICATION_URL, 409, $content);

        self::assertCount(0, $transport->get());
    }

    /**
     * @test
     */
    public function it_creates_new_verification_after_previous_was_expired(): void
    {
        $this->loadFixtures(static::getContainer());

        $content = [
            'subject' => [
                'type' => 'email_confirmation',
                'identity' => 'john.doe@abc.xyz',
            ],
        ];

        $response = $this->post(self::CREATE_VERIFICATION_URL, 201, $content);
        self::assertArrayHasKey('id', $response);

        $this->putRaw(sprintf(self::CONFIRM_VERIFICATION_URL, $response['id']), 422, ['code' => '123']);

        /** @var InMemoryTransport $transport */
        $transport = static::getContainer()
            ->get('messenger.transport.verification_confirmation_failed');

        self::assertCount(1, $transport->get());

        $this->post(self::CREATE_VERIFICATION_URL, 201, $content);
    }
}

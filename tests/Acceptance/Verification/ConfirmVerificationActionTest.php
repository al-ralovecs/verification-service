<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Verification;

use Component\Verification\Bridge\Doctrine\Repository\VerificationRepository;
use SN\TestFixturesBundle\FixturesTrait;
use SN\TestLib\Rest\RestTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;

final class ConfirmVerificationActionTest extends WebTestCase
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
    public function it_confirms_verification(): void
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

        /** @var VerificationRepository $verificationRepository */
        $verificationRepository = static::getContainer()
            ->get(VerificationRepository::class);
        $verification = $verificationRepository->getById($response['id']);

        $this->putRaw(sprintf(self::CONFIRM_VERIFICATION_URL, $response['id']), 204, ['code' => $verification->code()]);

        /** @var InMemoryTransport $transport */
        $transport = static::getContainer()
            ->get('messenger.transport.verification_confirmed');

        self::assertCount(1, $transport->get());
    }
}

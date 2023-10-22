<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Template;

use Component\Template\Enum\ContentType;
use Component\Template\Model\Template;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class SmsTemplateFixture extends Fixture
{
    public const TEMPLATE_SMS_ID = '018af1fd-8d24-72af-9066-01c6e3394b3e';
    public const TEMPLATE_SMS_SLUG = 'mobile-verification';
    public const TEMPLATE_SMS_CONTENT_TYPE = ContentType::TEXT_PLAIN;
    public const TEMPLATE_SMS_BODY = 'Your verification code is {{ code }}.';
    public const TEMPLATE_SMS_VARIABLES = ['code'];

    public function load(ObjectManager $manager): void
    {
        $template = new Template(
            self::TEMPLATE_SMS_ID,
            self::TEMPLATE_SMS_SLUG,
            self::TEMPLATE_SMS_CONTENT_TYPE,
            self::TEMPLATE_SMS_BODY,
            self::TEMPLATE_SMS_VARIABLES,
        );

        $manager->persist($template);
        $manager->flush();
    }
}

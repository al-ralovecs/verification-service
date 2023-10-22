<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Template;

use Component\Template\Enum\ContentType;
use Component\Template\Model\Template;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class EmailTemplateFixture extends Fixture
{
    public const TEMPLATE_EMAIL_ID = '018af1fd-8d04-7082-b134-1e2e79bdf35c';
    public const TEMPLATE_EMAIL_SLUG = 'email-verification';
    public const TEMPLATE_EMAIL_CONTENT_TYPE = ContentType::TEXT_HTML;
    public const TEMPLATE_EMAIL_BODY = <<<HTML
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
        <p>Your verification code is {{ code }}.</p>
    </div>
</body>
</html>
HTML;
    public const TEMPLATE_EMAIL_VARIABLES = ['code'];

    public function load(ObjectManager $manager): void
    {
        $template = new Template(
            self::TEMPLATE_EMAIL_ID,
            self::TEMPLATE_EMAIL_SLUG,
            self::TEMPLATE_EMAIL_CONTENT_TYPE,
            self::TEMPLATE_EMAIL_BODY,
            self::TEMPLATE_EMAIL_VARIABLES,
        );

        $manager->persist($template);
        $manager->flush();
    }
}

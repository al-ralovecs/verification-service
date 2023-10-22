<?php

declare(strict_types=1);

namespace Component\Verification\Checker;

use Component\Verification\Bridge\Doctrine\Dao\DuplicateVerificationCheckerDao;
use Component\Verification\Operator\VerificationSubjectInterface;
use DateInterval;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;

final readonly class DuplicateVerificationChecker implements DuplicateVerificationCheckerInterface
{
    public function __construct(
        private DuplicateVerificationCheckerDao $checkerDao,
        private DateInterval $ttl,
        private int $maxAttempts,
    ) {
    }

    /**
     * @throws Exception
     */
    public function duplicateExists(VerificationSubjectInterface $subject): bool
    {
        return $this->checkerDao->duplicateExists($subject, (new DateTimeImmutable())->sub($this->ttl), $this->maxAttempts);
    }
}

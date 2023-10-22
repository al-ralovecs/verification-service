<?php

declare(strict_types=1);

namespace Component\Template\Bridge\Doctrine\Repository;

use Component\Template\Exception\TemplateDoesNotExistException;
use Component\Template\Model\Template;
use Component\Template\Model\TemplateInterface;
use Component\Template\Operator\TemplateFetcherInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TemplateInterface>
 */
final class TemplateRepository extends ServiceEntityRepository implements TemplateFetcherInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Template::class);
    }

    public function getBySlug(string $slug): TemplateInterface
    {
        /** @var TemplateInterface|null $template */
        $template = $this->findOneBy(['slug' => $slug]);
        if (null === $template) {
            throw TemplateDoesNotExistException::missingSlug($slug);
        }

        return $template;
    }
}

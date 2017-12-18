<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Entity\Skill;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SkillRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     * @param null|string     $searchQuery
     * @param array           $searchableFields
     * @param null|string     $sortField
     * @param string          $sortDirection
     * @param null|string     $dqlFilter
     *
     * @return QueryBuilder
     */
    public static function createQueryBuilderForSearch(ManagerRegistry $managerRegistry, ?string $searchQuery, array $searchableFields = [], ?string $sortField, string $sortDirection = 'ASC', ?string $dqlFilter)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $managerRegistry->getManagerForClass(Skill::class);
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('entity');
        $queryBuilder->from(Skill::class, 'entity');
        $queryBuilder->join('entity.skillGroup', 'skillGroup');
        $queryBuilder->orWhere('LOWER(entity.name) LIKE :query');
        $queryBuilder->orWhere('LOWER(skillGroup.name) LIKE :query');
        $queryBuilder->setParameter('query', '%'.strtolower($searchQuery).'%');

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy('entity.'.$sortField, $sortDirection ?? 'DESC');
        }

        return $queryBuilder;
    }
}

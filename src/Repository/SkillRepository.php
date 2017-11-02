<?php

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Entity\Skill;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SkillRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     * @param $searchQuery
     * @param array $searchableFields
     * @param null  $sortField
     * @param null  $sortDirection
     * @param null  $dqlFilter
     *
     * @return QueryBuilder
     */
    public static function createQueryBuilderForSearch(ManagerRegistry $managerRegistry, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $managerRegistry->getManagerForClass(Skill::class);
        /* @var QueryBuilder $queryBuilder */
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

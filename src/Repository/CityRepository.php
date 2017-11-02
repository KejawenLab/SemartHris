<?php

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemartHris\Component\Address\Repository\CityRepositoryInterface;
use KejawenLab\Application\SemartHris\Entity\City;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class CityRepository extends Repository implements CityRepositoryInterface
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
        $entityManager = $managerRegistry->getManagerForClass(City::class);
        /* @var QueryBuilder $queryBuilder */
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('entity');
        $queryBuilder->from(City::class, 'entity');
        $queryBuilder->join('entity.region', 'region');
        $queryBuilder->orWhere('LOWER(entity.code) LIKE :query');
        $queryBuilder->orWhere('LOWER(entity.name) LIKE :query');
        $queryBuilder->orWhere('LOWER(region.code) LIKE :query');
        $queryBuilder->orWhere('LOWER(region.name) LIKE :query');
        $queryBuilder->setParameter('query', '%'.strtolower($searchQuery).'%');

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy('entity.'.$sortField, $sortDirection ?? 'DESC');
        }

        return $queryBuilder;
    }

    /**
     * @param string $regionId
     *
     * @return CityInterface[]
     */
    public function findCityByRegion(string $regionId): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o');
        $queryBuilder->from($this->entityClass, 'o');
        $queryBuilder->addSelect('o.id');
        $queryBuilder->addSelect('o.code');
        $queryBuilder->addSelect('o.name');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.region', $queryBuilder->expr()->literal($regionId)));

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $id
     *
     * @return null|CityInterface
     */
    public function find(string $id): ? CityInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->find($id);
    }
}

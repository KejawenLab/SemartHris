<?php

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
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
        $queryBuilder = $entityManager->createQueryBuilder()
            ->select('entity')
            ->from(City::class, 'entity')
            ->join('entity.region', 'region')
            ->orWhere('LOWER(entity.code) LIKE :query')
            ->orWhere('LOWER(entity.name) LIKE :query')
            ->orWhere('LOWER(region.code) LIKE :query')
            ->orWhere('LOWER(region.name) LIKE :query')
            ->setParameter('query', '%'.strtolower($searchQuery).'%')
        ;

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy('entity.'.$sortField, $sortDirection ?: 'DESC');
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
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository($this->entityClass);

        $queryBuilder = $repository->createQueryBuilder('c');
        $queryBuilder->addSelect('c.id');
        $queryBuilder->addSelect('c.code');
        $queryBuilder->addSelect('c.name');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('c.region', $queryBuilder->expr()->literal($regionId)));

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

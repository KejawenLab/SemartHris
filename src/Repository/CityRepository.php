<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemartHris\Component\Address\Repository\CityRepositoryInterface;
use KejawenLab\Application\SemartHris\Entity\City;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use KejawenLab\Application\SemartHris\Util\UuidUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CityRepository extends Repository implements CityRepositoryInterface
{
    /**
     * @param Request         $request
     * @param ManagerRegistry $managerRegistry
     * @param $searchQuery
     * @param array       $searchableFields
     * @param null|string $sortField
     * @param string      $sortDirection
     * @param null|string $dqlFilter
     *
     * @return QueryBuilder
     */
    public static function createQueryBuilderForSearch(Request $request, ManagerRegistry $managerRegistry, $searchQuery, array $searchableFields, ?string $sortField, string $sortDirection = 'ASC', ?string $dqlFilter)
    {
        $queryBuilder = self::createListQueryBuilder($request, $managerRegistry, $sortField, $sortDirection, $dqlFilter);
        $queryBuilder->orWhere($queryBuilder->expr()->like('entity.code', 'query'));
        $queryBuilder->orWhere($queryBuilder->expr()->like('entity.name', 'query'));
        $queryBuilder->setParameter('query', sprintf('%%%s%%', StringUtil::uppercase($searchQuery)));

        return $queryBuilder;
    }

    /**
     * @param Request         $request
     * @param ManagerRegistry $managerRegistry
     * @param null|string     $sortField
     * @param string          $sortDirection
     * @param null|string     $dqlFilter
     *
     * @return QueryBuilder
     */
    public static function createListQueryBuilder(Request $request, ManagerRegistry $managerRegistry, ?string $sortField, string $sortDirection = 'ASC', ?string $dqlFilter)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $managerRegistry->getManagerForClass(City::class);
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('entity');
        $queryBuilder->from(City::class, 'entity');

        $region = $request->getSession()->get('regionId');
        if ($region) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('entity.region', $queryBuilder->expr()->literal($region)));
        }

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy(sprintf('entity.%s', $sortField), $sortDirection);
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
        if (!$regionId || !UuidUtil::isValid($regionId)) {
            return [];
        }

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
    public function find(?string $id): ? CityInterface
    {
        return $this->doFind($id);
    }
}

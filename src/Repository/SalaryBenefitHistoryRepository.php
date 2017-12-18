<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Entity\SalaryBenefitHistory;
use KejawenLab\Application\SemartHris\Util\UuidUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SalaryBenefitHistoryRepository extends Repository
{
    use ContractableRepositoryTrait;

    /**
     * @param Request         $request
     * @param ManagerRegistry $managerRegistry
     * @param string          $sortDirection
     * @param null|string     $sortField
     * @param null|string     $dqlFilter
     *
     * @return QueryBuilder
     */
    public static function createListQueryBuilder(Request $request, ManagerRegistry $managerRegistry, string $sortDirection = 'ASC', ?string $sortField, ?string $dqlFilter): QueryBuilder
    {
        /** @var EntityManager $manager */
        $manager = $managerRegistry->getManagerForClass(SalaryBenefitHistory::class);
        $queryBuilder = $manager->createQueryBuilder();
        $queryBuilder->select('entity');
        $queryBuilder->from(SalaryBenefitHistory::class, 'entity');

        $employeeId = $request->query->get('employeeId');
        if ($employeeId && UuidUtil::isValid($employeeId)) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('entity.employee', $queryBuilder->expr()->literal($employeeId)));
        }

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy(sprintf('entity.%s', $sortField), $sortDirection);
        }

        return $queryBuilder;
    }
}

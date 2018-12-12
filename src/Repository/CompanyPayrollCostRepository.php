<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Entity\CompanyPayrollCost;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyPayrollCostRepository
{
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
        $now = new \DateTime();
        $year = $request->query->get('year', $now->format('Y'));
        $month = $request->query->get('month', $now->format('n'));

        /* @var EntityManagerInterface $entityManager */
        $entityManager = $managerRegistry->getManagerForClass(CompanyPayrollCost::class);
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('entity');
        $queryBuilder->from(CompanyPayrollCost::class, 'entity');
        $queryBuilder->innerJoin('entity.payroll', 'payroll');
        $queryBuilder->innerJoin('payroll.period', 'period');
        $queryBuilder->innerJoin('payroll.employee', 'employee');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('period.year', $queryBuilder->expr()->literal($year)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('period.month', $queryBuilder->expr()->literal($month)));

        if ($company = $request->query->get('company')) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('employee.company', $queryBuilder->expr()->literal($company)));
        }

        if ($employee = $request->query->get('employeeId')) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('employee.id', $queryBuilder->expr()->literal($employee)));
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

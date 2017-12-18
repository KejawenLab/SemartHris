<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Model\TaxInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Repository\TaxRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class TaxRepository extends Repository implements TaxRepositoryInterface
{
    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return TaxInterface|null
     */
    public function findByEmployeeAndPeriod(EmployeeInterface $employee, PayrollPeriodInterface $period): ? TaxInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy([
            'employee' => $employee,
            'period' => $period,
        ]);
    }

    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return TaxInterface
     */
    public function createTax(EmployeeInterface $employee, PayrollPeriodInterface $period): TaxInterface
    {
        $tax = $this->findByEmployeeAndPeriod($employee, $period);
        if (!$tax) {
            /** @var TaxInterface $tax */
            $tax = new $this->entityClass();
            $tax->setEmployee($employee);
            $tax->setPeriod($period);
            $tax->setTaxGroup($employee->getTaxGroup());
        }

        return $tax;
    }

    /**
     * @param TaxInterface $tax
     */
    public function update(TaxInterface $tax): void
    {
        $this->entityManager->persist($tax);
        $this->entityManager->flush();
    }

    /**
     * @param Request     $request
     * @param null|string $sortField
     * @param string      $sortDirection
     * @param null|string $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder(Request $request, ?string $sortField, string $sortDirection = 'ASC', ?string $dqlFilter)
    {
        $now = new \DateTime();
        $year = $request->query->get('year', $now->format('Y'));
        $month = $request->query->get('month', $now->format('n'));

        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('entity');
        $queryBuilder->from($this->entityClass, 'entity');
        $queryBuilder->innerJoin('entity.period', 'period');
        $queryBuilder->innerJoin('entity.employee', 'employee');
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

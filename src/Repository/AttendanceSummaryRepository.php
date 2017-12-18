<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\AttendanceSummaryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\AttendanceSummaryRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceSummaryRepository extends Repository implements AttendanceSummaryRepositoryInterface
{
    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return AttendanceSummaryInterface|null
     */
    public function findByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $date): ? AttendanceSummaryInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy([
            'employee' => $employee,
            'year' => $date->format('Y'),
            'month' => $date->format('n'),
        ]);
    }

    /**
     * @param Request     $request
     * @param string      $sortDirection
     * @param null|string $sortField
     * @param null|string $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder(Request $request, string $sortDirection = 'ASC', ?string $sortField, ?string $dqlFilter): QueryBuilder
    {
        $now = new \DateTime();
        $year = $request->query->get('year', $now->format('Y'));
        $month = $request->query->get('month', $now->format('n'));

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('entity');
        $queryBuilder->from($this->entityClass, 'entity');
        $queryBuilder->innerJoin('entity.employee', 'employee');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('entity.year', $queryBuilder->expr()->literal($year)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('entity.month', $queryBuilder->expr()->literal($month)));

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

    /**
     * @param AttendanceSummaryInterface $summary
     */
    public function update(AttendanceSummaryInterface $summary): void
    {
        $manager = $this->entityManager;
        $manager->persist($summary);
        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\WorkshiftInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\WorkshiftRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class WorkshiftRepository extends Repository implements WorkshiftRepositoryInterface
{
    /**
     * @param WorkshiftInterface $workshift
     */
    public function update(WorkshiftInterface $workshift): void
    {
        $this->entityManager->persist($workshift);
        $this->entityManager->flush();
    }

    /**
     * @param WorkshiftInterface $workshift
     *
     * @return WorkshiftInterface|null
     */
    public function findInterSectionWorkshift(WorkshiftInterface $workshift): ? WorkshiftInterface
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('w');
        $queryBuilder->from($this->entityClass, 'w');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('w.employee', $queryBuilder->expr()->literal($workshift->getEmployee()->getId())));
        $queryBuilder->andWhere($queryBuilder->expr()->lt('w.startDate', $queryBuilder->expr()->literal($workshift->getStartDate()->format('Y-m-d'))));
        $queryBuilder->andWhere($queryBuilder->expr()->gt('w.endDate', $queryBuilder->expr()->literal($workshift->getEndDate()->format('Y-m-d'))));

        return  $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @param string|null        $companyId
     * @param string|null        $departmentId
     * @param string|null        $shiftmentId
     * @param array              $sorts
     *
     * @return QueryBuilder
     */
    public function getFilteredWorkshift(\DateTimeInterface $startDate, \DateTimeInterface $endDate, ?string $companyId, ?string $departmentId, ?string $shiftmentId, array $sorts = []): QueryBuilder
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('w');
        $queryBuilder->from($this->entityClass, 'w');
        $queryBuilder->leftJoin('w.employee', 'e');
        $queryBuilder->andWhere($queryBuilder->expr()->gte('w.startDate', $queryBuilder->expr()->literal($startDate->format('Y-m-d'))));
        $queryBuilder->andWhere($queryBuilder->expr()->lte('w.startDate', $queryBuilder->expr()->literal($endDate->format('Y-m-d'))));

        if ($companyId) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('e.company', $queryBuilder->expr()->literal($companyId)));
        }

        if ($departmentId) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('e.department', $queryBuilder->expr()->literal($departmentId)));
        }

        if ($shiftmentId) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('w.shiftment', $queryBuilder->expr()->literal($shiftmentId)));
        }

        if (!empty($sorts)) {
            foreach ($sorts as $field => $direction) {
                $queryBuilder->addOrderBy(sprintf('w.%s', $field), $direction);
            }
        }

        return  $queryBuilder;
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return WorkshiftInterface|null
     */
    public function findByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $date): ? WorkshiftInterface
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('w');
        $queryBuilder->from($this->entityClass, 'w');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('w.employee', $queryBuilder->expr()->literal($employee->getId())));
        $queryBuilder->andWhere($queryBuilder->expr()->lte('w.startDate', $queryBuilder->expr()->literal($date->format('Y-m-d'))));
        $queryBuilder->andWhere($queryBuilder->expr()->gte('w.endDate', $queryBuilder->expr()->literal($date->format('Y-m-d'))));

        return  $queryBuilder->getQuery()->getOneOrNullResult();
    }
}

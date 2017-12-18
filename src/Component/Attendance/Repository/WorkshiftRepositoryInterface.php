<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Attendance\Repository;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\WorkshiftInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface WorkshiftRepositoryInterface
{
    /**
     * @param WorkshiftInterface $workshift
     */
    public function update(WorkshiftInterface $workshift): void;

    /**
     * @param WorkshiftInterface $workshift
     *
     * @return WorkshiftInterface|null
     */
    public function findInterSectionWorkshift(WorkshiftInterface $workshift): ? WorkshiftInterface;

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
    public function getFilteredWorkshift(\DateTimeInterface $startDate, \DateTimeInterface $endDate, ?string $companyId, ?string $departmentId, ?string $shiftmentId, array $sorts = []): QueryBuilder;

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return WorkshiftInterface|null
     */
    public function findByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $date): ? WorkshiftInterface;
}

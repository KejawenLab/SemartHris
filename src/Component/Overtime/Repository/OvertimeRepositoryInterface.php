<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Overtime\Repository;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface OvertimeRepositoryInterface
{
    /**
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @param string|null        $companyId
     * @param string|null        $departmentId
     * @param string|null        $shiftmentId
     * @param string|null        $employeeId
     * @param array              $sorts
     *
     * @return QueryBuilder
     */
    public function getFilteredOvertime(\DateTimeInterface $startDate, \DateTimeInterface $endDate, ?string $companyId, ?string $departmentId, ?string $shiftmentId, ?string $employeeId, array $sorts = []): QueryBuilder;

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return OvertimeInterface|null
     */
    public function findByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $date): ? OvertimeInterface;

    /**
     * @param OvertimeInterface $overtime
     */
    public function update(OvertimeInterface $overtime): void;

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $from
     * @param \DateTimeInterface $to
     *
     * @return array
     */
    public function getSummaryByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $from, \DateTimeInterface $to): array;
}

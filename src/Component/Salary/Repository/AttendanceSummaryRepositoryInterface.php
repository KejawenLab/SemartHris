<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\AttendanceSummaryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface AttendanceSummaryRepositoryInterface
{
    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return AttendanceSummaryInterface|null
     */
    public function findByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $date): ? AttendanceSummaryInterface;

    /**
     * @param AttendanceSummaryInterface $summary
     */
    public function update(AttendanceSummaryInterface $summary): void;
}

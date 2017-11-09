<?php

namespace KejawenLab\Application\SemartHris\Component\Attendance\Repository;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface AttendanceRepositoryInterface
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
    public function getFilteredAttendance(\DateTimeInterface $startDate, \DateTimeInterface $endDate, string $companyId = null, string $departmentId = null, string $shiftmentId = null, string $employeeId = null, array $sorts = []): QueryBuilder;

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return AttendanceInterface|null
     */
    public function findByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $date): ? AttendanceInterface;

    /**
     * @param \DateTimeInterface $date
     *
     * @return AttendanceInterface[]
     */
    public function findByDate(\DateTimeInterface $date): array;

    /**
     * @param AttendanceInterface $attendance
     */
    public function update(AttendanceInterface $attendance): void;
}

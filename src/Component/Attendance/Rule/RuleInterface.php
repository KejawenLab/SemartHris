<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Attendance\Rule;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface RuleInterface
{
    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $attendanceDate
     *
     * @return AttendanceInterface
     *
     * @throws NotQualifiedException
     */
    public function apply(EmployeeInterface $employee, \DateTimeInterface $attendanceDate): AttendanceInterface;
}

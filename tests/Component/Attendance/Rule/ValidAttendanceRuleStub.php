<?php

namespace Tests\KejawenLab\Application\SemartHris\Component\Attendance\Rule;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Rule\NotQualifiedException;
use KejawenLab\Application\SemartHris\Component\Attendance\Rule\RuleInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidAttendanceRuleStub implements RuleInterface
{
    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $attendanceDate
     *
     * @return AttendanceInterface
     *
     * @throws NotQualifiedException
     */
    public function apply(EmployeeInterface $employee, \DateTimeInterface $attendanceDate): AttendanceInterface
    {
        throw new NotQualifiedException();
    }
}

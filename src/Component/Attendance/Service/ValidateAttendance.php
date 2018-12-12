<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Attendance\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidateAttendance
{
    /**
     * @param AttendanceInterface $attendance
     *
     * @return bool
     */
    public static function validate(AttendanceInterface $attendance): bool
    {
        if ($attendance->isAbsent() && null === $attendance->getReason()) {
            return false;
        }

        if (false === $attendance->isAbsent() && (null === $attendance->getCheckIn() || null === $attendance->getCheckOut())) {
            return false;
        }

        return true;
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Overtime\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Repository\AttendanceRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OvertimeChecker
{
    /**
     * @var AttendanceRepositoryInterface
     */
    private $attendanceRepository;

    /**
     * @param AttendanceRepositoryInterface $repository
     */
    public function __construct(AttendanceRepositoryInterface $repository)
    {
        $this->attendanceRepository = $repository;
    }

    /**
     * @param OvertimeInterface $overtime
     *
     * @return bool
     */
    public function allowToOvertime(OvertimeInterface $overtime): bool
    {
        $employee = $overtime->getEmployee();
        if (!$employee->isHaveOvertimeBenefit()) {
            return false;
        }

        if (!$attendance = $this->attendanceRepository->findByEmployeeAndDate($employee, $overtime->getOvertimeDate())) {
            return false;
        }

        if ($attendance->isAbsent()) {
            return false;
        }

        if ($overtime->getStartHour() < $attendance->getCheckIn()) {//When start date less then check in, let me use check in
            $overtime->setStartHour($attendance->getCheckIn());
        }

        if ($overtime->getEndHour() > $attendance->getCheckOut()) {//When end date more then check out, let me use check out
            $overtime->setEndHour($attendance->getCheckOut());
        }

        return true;
    }
}

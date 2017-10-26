<?php

namespace KejawenLab\Application\SemartHris\Component\Overtime\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Repository\AttendanceRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class OvertimeChecker
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

        if (!$this->attendanceRepository->findByEmployeeAndDate($employee, $overtime->getOvertimeDate())) {
            return false;
        }

        return true;
    }
}

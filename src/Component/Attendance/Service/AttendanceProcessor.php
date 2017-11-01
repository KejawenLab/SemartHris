<?php

namespace KejawenLab\Application\SemartHris\Component\Attendance\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\AttendanceRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\WorkshiftRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Holiday\Repository\HolidayRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Repository\ReasonRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class AttendanceProcessor
{
    const CUT_OFF_LAST_DATE = -1;
    const CUT_OFF_KEY = 'SEMART_ATTENDANCE_CUT_OFF_DATE';

    /**
     * @var AttendanceRepositoryInterface
     */
    private $attendanceRepository;

    /**
     * @var HolidayRepositoryInterface
     */
    private $holidayRepository;

    /**
     * @var ReasonRepositoryInterface
     */
    private $reasonRepository;

    /**
     * @var WorkshiftRepositoryInterface
     */
    private $workshiftRepository;

    /**
     * @var string
     */
    private $reasonCode;

    /**
     * @var string
     */
    private $class;

    /**
     * @param AttendanceRepositoryInterface $attendanceRepository
     * @param HolidayRepositoryInterface    $holidayRepository
     * @param ReasonRepositoryInterface     $reasonRepository
     * @param WorkshiftRepositoryInterface  $workshiftRepository
     * @param string                        $reasonCode
     * @param string                        $attendanceClass
     */
    public function __construct(
        AttendanceRepositoryInterface $attendanceRepository,
        HolidayRepositoryInterface $holidayRepository,
        ReasonRepositoryInterface $reasonRepository,
        WorkshiftRepositoryInterface $workshiftRepository,
        string $reasonCode,
        string $attendanceClass
    ) {
        $this->attendanceRepository = $attendanceRepository;
        $this->holidayRepository = $holidayRepository;
        $this->reasonRepository = $reasonRepository;
        $this->workshiftRepository = $workshiftRepository;
        $this->reasonCode = $reasonCode;
        $this->class = $attendanceClass;
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     */
    public function process(EmployeeInterface $employee, \DateTimeInterface $date): void
    {
        $cutOff = getenv(self::CUT_OFF_KEY);
        if ((int) $cutOff === self::CUT_OFF_LAST_DATE) {
            $this->processFullMonth($employee, $date);
        } else {
            $this->processPartialMonth($employee, $date, $cutOff);
        }
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     */
    private function processFullMonth(EmployeeInterface $employee, \DateTimeInterface $date): void
    {
        $count = $date->format('t');
        for ($i = 1; $i <= $count; ++$i) {
            $attendanceDate = \DateTime::createFromFormat('Y-m-j', sprintf('%s-%d', $date->format('Y-m'), $i));
            $this->doProcess($employee, $attendanceDate);
        }
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     * @param int                $cutOff
     */
    private function processPartialMonth(EmployeeInterface $employee, \DateTimeInterface $date, int $cutOff): void
    {
        /** @var \DateTime $date */
        $countPrevMonth = $date->sub(new \DateInterval('P1M'))->format('t');
        for ($i = ($cutOff + 1); $i <= $countPrevMonth; ++$i) {
            $attendanceDate = \DateTime::createFromFormat('Y-m-j', sprintf('%s-%d', $date->format('Y-m'), $i));
            $this->doProcess($employee, $attendanceDate);
        }

        for ($i = 1; $i <= $cutOff; ++$i) {
            $attendanceDate = \DateTime::createFromFormat('Y-m-j', sprintf('%s-%d', $date->format('Y-m'), $i));
            $this->doProcess($employee, $attendanceDate);
        }
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     */
    private function doProcess(EmployeeInterface $employee, \DateTimeInterface $date): void
    {
        $attendance = $this->attendanceRepository->findByEmployeeAndDate($employee, $date);
        if ($this->holidayRepository->isHoliday($date) && !$attendance) {
            return;
        }

        if (!$attendance) {
            $workshift = $this->workshiftRepository->findByEmployeeAndDate($employee, $date);

            /** @var AttendanceInterface $attendance */
            $attendance = new $this->class();
            $attendance->setEmployee($employee);
            $attendance->setAttendanceDate($date);
            $attendance->setShiftment($workshift ? $workshift->getShiftment() : null);
            $attendance->setReason($this->reasonRepository->findByCode($this->reasonCode));
            $attendance->setAbsent(true);
        }

        $this->attendanceRepository->update($attendance);
    }
}

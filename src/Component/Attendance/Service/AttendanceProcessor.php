<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Attendance\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\AttendanceRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\WorkshiftRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Rule\NotQualifiedException;
use KejawenLab\Application\SemartHris\Component\Attendance\Rule\RuleInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Holiday\Repository\HolidayRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Repository\ReasonRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceProcessor
{
    const CUT_OFF_LAST_DATE = -1;

    /**
     * @var RuleInterface
     */
    private $attendanceRule;

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
     * @var int
     */
    private $cutOffDate;

    /**
     * @var string
     */
    private $class;

    /**
     * @param RuleInterface                 $attendanceRule
     * @param AttendanceRepositoryInterface $attendanceRepository
     * @param HolidayRepositoryInterface    $holidayRepository
     * @param ReasonRepositoryInterface     $reasonRepository
     * @param WorkshiftRepositoryInterface  $workshiftRepository
     * @param string                        $reasonCode
     * @param int                           $cutOffDate
     * @param string                        $attendanceClass
     */
    public function __construct(
        RuleInterface $attendanceRule,
        AttendanceRepositoryInterface $attendanceRepository,
        HolidayRepositoryInterface $holidayRepository,
        ReasonRepositoryInterface $reasonRepository,
        WorkshiftRepositoryInterface $workshiftRepository,
        string $reasonCode,
        int $cutOffDate,
        string $attendanceClass
    ) {
        $this->attendanceRule = $attendanceRule;
        $this->attendanceRepository = $attendanceRepository;
        $this->holidayRepository = $holidayRepository;
        $this->reasonRepository = $reasonRepository;
        $this->workshiftRepository = $workshiftRepository;
        $this->reasonCode = $reasonCode;
        $this->cutOffDate = $cutOffDate;
        $this->class = $attendanceClass;
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     */
    public function process(EmployeeInterface $employee, \DateTimeInterface $date): void
    {
        if (self::CUT_OFF_LAST_DATE === $this->cutOffDate) {
            $this->processFullMonth($employee, $date);
        } else {
            $this->processPartialMonth($employee, $date, $this->cutOffDate);
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
        if (!$attendance) {
            try {
                $attendance = $this->attendanceRule->apply($employee, $date);
            } catch (NotQualifiedException $exception) {
                //Do nothing
            }
        }

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
            $attendance->setReason($this->reasonRepository->findAbsentReasonByCode($this->reasonCode));
            $attendance->setAbsent(true);
        }
        $attendance->setLateIn(-1);

        $this->attendanceRepository->update($attendance);
    }
}

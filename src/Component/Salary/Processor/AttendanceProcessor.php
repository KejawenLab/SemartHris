<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Processor;

use KejawenLab\Application\SemartHris\Component\Attendance\Repository\AttendanceRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Repository\OvertimeRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\AttendanceSummaryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\AttendanceSummaryRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Service\WorkdayCalculator;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceProcessor implements PayrollProcessorInterface
{
    const CUT_OFF_LAST_DATE = -1;

    /**
     * @var WorkdayCalculator
     */
    private $workdayCalculator;

    /**
     * @var AttendanceRepositoryInterface
     */
    private $attendanceRepository;

    /**
     * @var OvertimeRepositoryInterface
     */
    private $overtimeRepository;

    /**
     * @var AttendanceSummaryRepositoryInterface
     */
    private $attendanceSummaryRepository;

    /**
     * @var Setting
     */
    private $setting;

    /**
     * @var int
     */
    private $cutOffDate;

    /**
     * @var string
     */
    private $attendanceSummaryClass;

    /**
     * AttendanceProcessor constructor.
     *
     * @param WorkdayCalculator                    $workdayCalculator
     * @param AttendanceRepositoryInterface        $attendanceRepository
     * @param OvertimeRepositoryInterface          $overtimeRepository
     * @param AttendanceSummaryRepositoryInterface $attendanceSummaryRepository
     * @param Setting                              $setting
     * @param int                                  $cutOffDate
     * @param string                               $attendanceSummaryClass
     */
    public function __construct(
        WorkdayCalculator $workdayCalculator,
        AttendanceRepositoryInterface $attendanceRepository,
        OvertimeRepositoryInterface $overtimeRepository,
        AttendanceSummaryRepositoryInterface $attendanceSummaryRepository,
        Setting $setting,
        int $cutOffDate,
        string $attendanceSummaryClass
    ) {
        $this->workdayCalculator = $workdayCalculator;
        $this->attendanceRepository = $attendanceRepository;
        $this->overtimeRepository = $overtimeRepository;
        $this->attendanceSummaryRepository = $attendanceSummaryRepository;
        $this->setting = $setting;
        $this->cutOffDate = $cutOffDate;
        $this->attendanceSummaryClass = $attendanceSummaryClass;
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     */
    public function process(EmployeeInterface $employee, \DateTimeInterface $date): void
    {
        /* @var AttendanceSummaryInterface $summary */
        $summary = $this->attendanceSummaryRepository->findByEmployeeAndDate($employee, $date);
        if (!$summary) {
            $summary = new $this->attendanceSummaryClass();
            $summary->setEmployee($employee);
            $summary->setYear((int) $date->format('Y'));
            $summary->setMonth((int) $date->format('n'));
        }

        if (self::CUT_OFF_LAST_DATE === $this->cutOffDate) {
            $totalWorkday = $this->workdayCalculator->getWorkdays($date);
            $summary->setTotalWorkday($totalWorkday);

            $from = \DateTime::createFromFormat($this->setting->get(SettingKey::DATE_FORMAT), $date->format($this->setting->get(SettingKey::FIRST_DATE_FORMAT)));
            $to = \DateTime::createFromFormat($this->setting->get(SettingKey::DATE_FORMAT), $date->format($this->setting->get(SettingKey::LAST_DATE_FORMAT)));

            $this->applyAttendanceSummary($summary, $from, $to);
        } else {
            /** @var \DateTime $prev */
            $prev = clone $date;
            $prev->sub(new \DateInterval('P1M'));
            $preWorkday = $this->workdayCalculator->getWorkdays($prev, 0, ($this->cutOffDate + 1));
            $currWorkday = $this->workdayCalculator->getWorkdays($date, $this->cutOffDate);

            $totalWorkday = $preWorkday + $currWorkday;
            $summary->setTotalWorkday($totalWorkday);

            $from = \DateTime::createFromFormat('Y-m-d', sprintf('%s-%s', $prev->format('Y-m'), ($this->cutOffDate + 1)));
            $to = \DateTime::createFromFormat('Y-m-d', sprintf('%s-%s', $date->format('Y-m'), $this->cutOffDate));

            $this->applyAttendanceSummary($summary, $from, $to);
        }

        $this->attendanceSummaryRepository->update($summary);
    }

    /**
     * @param AttendanceSummaryInterface $summary
     * @param \DateTimeInterface         $from
     * @param \DateTimeInterface         $to
     */
    private function applyAttendanceSummary(AttendanceSummaryInterface $summary, \DateTimeInterface $from, \DateTimeInterface $to): void
    {
        $attendanceSummary = $this->attendanceRepository->getSummaryByEmployeeAndDate($summary->getEmployee(), $from, $to);
        $totalAbsent = $summary->getTotalWorkday() - $attendanceSummary['totalIn'];
        $totalLoyality = ($attendanceSummary['earlyIn'] - $attendanceSummary['lateIn']) + ($attendanceSummary['lateOut'] - $attendanceSummary['earlyOut']);

        $summary->setTotalIn($attendanceSummary['totalIn'] ?? 0);
        $summary->setTotalAbsent($totalAbsent ?? 0);
        $summary->setTotalLoyality($totalLoyality ?? 0);

        $totalOvertime = $this->overtimeRepository->getSummaryByEmployeeAndDate($summary->getEmployee(), $from, $to);
        $summary->setTotalOvertime((int) $totalOvertime['totalOvertime'] ?? 0);
    }
}

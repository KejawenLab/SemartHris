<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Overtime\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Repository\WorkshiftRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Calculator\OvertimeCalculator as Calculator;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;
use KejawenLab\Application\SemartHris\Kernel;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OvertimeCalculator
{
    /**
     * @var OvertimeChecker
     */
    private $checker;

    /**
     * @var Calculator
     */
    private $calculator;

    /**
     * @var WorkshiftRepositoryInterface
     */
    private $workshiftRepository;

    /**
     * @var Setting
     */
    private $setting;

    /**
     * @var int
     */
    private $workDay;

    /**
     * @param OvertimeChecker              $checker
     * @param Calculator                   $calculator
     * @param WorkshiftRepositoryInterface $repository
     * @param Setting                      $setting
     * @param int                          $workDayPerWeek
     */
    public function __construct(
        OvertimeChecker $checker,
        Calculator $calculator,
        WorkshiftRepositoryInterface $repository,
        Setting $setting,
        int $workDayPerWeek
    ) {
        $this->checker = $checker;
        $this->calculator = $calculator;
        $this->workshiftRepository = $repository;
        $this->setting = $setting;
        $this->workDay = $workDayPerWeek;
    }

    /**
     * @param OvertimeInterface $overtime
     */
    public function calculate(OvertimeInterface $overtime): void
    {
        $overtime->setDescription(str_replace(sprintf('%s#', Kernel::SEMART_VERSION), '', $overtime->getDescription()));
        if (!$this->checker->allowToOvertime($overtime)) {
            $this->setInvalid($overtime);

            return;
        }

        if (!$overtime->getEndHour()) {
            $this->setInvalid($overtime);

            return;
        }

        $workshift = $this->workshiftRepository->findByEmployeeAndDate($overtime->getEmployee(), $overtime->getOvertimeDate());
        if (!$workshift) {
            $this->setInvalid($overtime);

            return;
        }

        $overtime->setShiftment($workshift->getShiftment());
        $this->calculator->setWorkdayPerWeek($this->workDay);
        $this->calculator->setSetingg($this->setting);
        $this->calculator->calculate($overtime);
    }

    /**
     * @param OvertimeInterface $overtime
     */
    private function setInvalid(OvertimeInterface $overtime): void
    {
        $overtime->setDescription($this->setting->get(SettingKey::OVERTIME_INVALID_MESSAGE));
        $overtime->setApprovedBy(null);
        $overtime->setRawValue((float) 0);
        $overtime->setCalculatedValue((float) 0);
        $overtime->setHoliday(false);
        $overtime->setOverday(false);
    }
}

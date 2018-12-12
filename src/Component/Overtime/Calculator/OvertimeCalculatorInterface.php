<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Overtime\Calculator;

use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface OvertimeCalculatorInterface
{
    /**
     * @param OvertimeInterface $overtime
     */
    public function calculate(OvertimeInterface $overtime): void;

    /**
     * @param int $workday
     */
    public function setWorkdayPerWeek(int $workday): void;

    /**
     * @param Setting $setting
     */
    public function setSetingg(Setting $setting): void;
}

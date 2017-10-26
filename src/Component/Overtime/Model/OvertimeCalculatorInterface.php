<?php

namespace KejawenLab\Application\SemartHris\Component\Overtime\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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
}

<?php

namespace KejawenLab\Application\SemartHris\Component\Overtime\Calculator;

use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class WorkdayCalculator extends Calculator
{
    /**
     * @param OvertimeInterface $overtime
     */
    public function calculate(OvertimeInterface $overtime): void
    {
        if ($overtime->isHoliday()) {
            throw new CalculatorException();
        }

        $hours = $this->getOvertimeHours($overtime);
        //1 first hour multiply with 1.5
        $calculatedValue = 1.5 * 1;
        --$hours;
        if (0 < $hours) {
            $calculatedValue += (2 * $hours); //2 hours in second, multiply with 2
            $hours -= 2;
        }

        if (0 < $hours) {
            $calculatedValue += (3 * $hours); //Others, multiply with 3
        }

        $overtime->setCalculatedValue($calculatedValue);
    }
}

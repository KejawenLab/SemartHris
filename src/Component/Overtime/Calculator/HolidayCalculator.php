<?php

namespace KejawenLab\Application\SemartHris\Component\Overtime\Calculator;

use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class HolidayCalculator extends Calculator
{
    /**
     * @param OvertimeInterface $overtime
     */
    public function calculate(OvertimeInterface $overtime): void
    {
        if (!$overtime->isHoliday()) {
            throw new CalculatorException();
        }

        $hours = $this->getOvertimeHours($overtime);
        //8 first hour multiply with 2
        $calculatedValue = 8 * 2;
        $hours -= 8;
        if (0 < $hours) {
            $calculatedValue += (3 * $hours); //Others, multiply with 3
        }

        $overtime->setCalculatedValue($calculatedValue);
    }
}

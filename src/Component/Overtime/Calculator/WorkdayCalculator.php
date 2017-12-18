<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Overtime\Calculator;

use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 *
 * @see https://gajimu.com/main/pekerjaan-yanglayak/kompensasi/upah-lembur
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
        $overtime->setRawValue($hours);
        //1 first hour multiply with 1.5
        $calculatedValue = 1.5 * 1;
        --$hours;
        if (0 < $hours) {
            $calculatedValue += (2 * $hours); //Others, multiply with 2
        }

        $overtime->setCalculatedValue($calculatedValue);
    }
}

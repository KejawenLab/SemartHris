<?php

namespace KejawenLab\Application\SemartHris\Component\Overtime\Calculator;

use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeCalculatorInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
abstract class Calculator implements OvertimeCalculatorInterface
{
    /**
     * @param OvertimeInterface $overtime
     *
     * @return int
     */
    protected function getOvertimeHours(OvertimeInterface $overtime): int
    {
        /** @var \DateTime $endHour */
        $endHour = $overtime->getEndHour();
        if ($overtime->isOverday()) {
            $endHour->add(new \DateInterval('P1D'));
        }

        $delta = $overtime->getEndHour()->diff($overtime->getStartHour(), true);
        $hours = $delta->h;
        $minutes = $delta->i;
        if (45 >= $minutes) {//More than 45 minutes count as 1 hour
            ++$hours;
        }

        return $hours;
    }
}

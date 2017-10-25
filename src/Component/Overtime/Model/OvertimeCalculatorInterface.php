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
}

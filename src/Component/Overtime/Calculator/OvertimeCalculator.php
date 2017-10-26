<?php

namespace KejawenLab\Application\SemartHris\Component\Overtime\Calculator;

use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeCalculatorInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class OvertimeCalculator implements OvertimeCalculatorInterface
{
    const SEMARTHRIS_OVERTIME_CALCULATOR = 'semarthris.overtime_calculator';

    /**
     * @var OvertimeCalculatorInterface[]
     */
    private $calculators;

    /**
     * @param OvertimeCalculatorInterface[] $calculators
     */
    public function __construct(array $calculators = [])
    {
        foreach ($calculators as $calculator) {
            $this->addCalculator($calculator);
        }
    }

    /**
     * @param OvertimeInterface $overtime
     */
    public function calculate(OvertimeInterface $overtime): void
    {
        foreach ($this->calculators as $calculator) {
            try {
                $calculator->calculate($overtime);
            } catch (CalculatorException $exception) {
                continue;
            }
        }
    }

    /**
     * @param OvertimeCalculatorInterface $calculator
     */
    private function addCalculator(OvertimeCalculatorInterface $calculator)
    {
        $this->calculators[] = $calculator;
    }
}

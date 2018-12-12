<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Overtime\Calculator;

use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OvertimeCalculator extends Calculator implements OvertimeCalculatorInterface
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
        $this->calculators = [];
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
                $calculator->setWorkdayPerWeek($this->workday);
                $calculator->setSetingg($this->setting);
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

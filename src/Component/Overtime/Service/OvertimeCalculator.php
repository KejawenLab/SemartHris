<?php

namespace KejawenLab\Application\SemartHris\Component\Overtime\Service;

use KejawenLab\Application\SemartHris\Component\Overtime\Calculator\CalculatorException;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeCalculatorInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class OvertimeCalculator implements OvertimeCalculatorInterface
{
    /**
     * @var OvertimeCalculatorInterface[]
     */
    private $formulas;

    /**
     * @param OvertimeCalculatorInterface[] $formulas
     */
    public function __construct(array $formulas = [])
    {
        foreach ($formulas as $formula) {
            $this->addFormula($formula);
        }
    }

    /**
     * @param OvertimeInterface $overtime
     */
    public function calculate(OvertimeInterface $overtime): void
    {
        foreach ($this->formulas as $formula) {
            try {
                $formula->calculate($overtime);
            } catch (CalculatorException $exception) {
                continue;
            }
        }
    }

    /**
     * @param OvertimeCalculatorInterface $formula
     */
    private function addFormula(OvertimeCalculatorInterface $formula)
    {
        $this->formulas[] = $formula;
    }
}

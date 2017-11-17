<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Service\RiskRatioConverter;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class BpjsProcessor implements SalaryProcessorInterface
{
    /**
     * @param PayrollInterface   $payroll
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     * @param float              $fixedSalary
     *
     * @return float
     */
    public function process(PayrollInterface $payroll, EmployeeInterface $employee, \DateTimeInterface $date, float $fixedSalary): float
    {
        $riskRatioValue = RiskRatioConverter::getValue($employee->getRiskRatio());

        return $fixedSalary;
    }

    /**
     * @param float $riskRatioValue
     * @param float $fixedSalary
     */
    private function processJkk(float $riskRatioValue, float $fixedSalary): void
    {
    }

    /**
     * @param float $riskRatioValue
     * @param float $fixedSalary
     */
    private function processJkm(float $riskRatioValue, float $fixedSalary): void
    {
    }

    /**
     * @param float $riskRatioValue
     * @param float $fixedSalary
     */
    private function processJp(float $riskRatioValue, float $fixedSalary): void
    {
    }

    /**
     * @param float $riskRatioValue
     * @param float $fixedSalary
     */
    private function processJht(float $riskRatioValue, float $fixedSalary): void
    {
    }
}

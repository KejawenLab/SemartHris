<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Service\TaxPercentage;
use KejawenLab\Application\SemartHris\Component\Tax\TaxGroup;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
abstract class Processor implements TaxProcessorInterface
{
    /**
     * @var PayrollRepositoryInterface
     */
    protected $payrollRepository;

    /**
     * @param PayrollRepositoryInterface $payrollRepository
     */
    public function setPayrollRepository(PayrollRepositoryInterface $payrollRepository): void
    {
        $this->payrollRepository = $payrollRepository;
    }

    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return float
     */
    protected function calculate(EmployeeInterface $employee, PayrollPeriodInterface $period): float
    {
        $payroll = $this->payrollRepository->findPayroll($employee, $period);
        if (!$payroll) {
            throw new TaxProcessorException();
        }

        $takeHomePay = (float) $payroll->getTakeHomePay();
        $taxable = (12 * $payroll->getTakeHomePay()) - TaxGroup::$PTKP[$employee->getTaxGroup()];
        $percentage = TaxPercentage::getValue($takeHomePay);

        return round($taxable * $percentage / 12, 0, PHP_ROUND_HALF_DOWN);
    }
}

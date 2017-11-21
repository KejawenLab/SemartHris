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
     * @var float
     */
    private $taxable = 0.0;

    /**
     * @var float
     */
    private $untaxable = 0.0;

    /**
     * @var float
     */
    private $taxPercentage = 0.0;

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
        $this->untaxable = (float) TaxGroup::$PTKP[$employee->getTaxGroup()];
        $this->taxable = (12 * $takeHomePay) - $this->untaxable;
        $this->taxPercentage = TaxPercentage::getValue($takeHomePay);

        return round(($this->taxable * $this->taxPercentage) / 12, 0, PHP_ROUND_HALF_DOWN);
    }

    /**
     * @return float
     */
    public function getTaxableValue(): float
    {
        return $this->taxable;
    }

    /**
     * @param float|null $taxable
     */
    public function setTaxableValue(?float $taxable): void
    {
        $this->taxable = $taxable;
    }

    /**
     * @return float
     */
    public function getUntaxableValue(): float
    {
        return $this->untaxable;
    }

    /**
     * @param float|null $untaxable
     */
    public function setUntaxableValue(?float $untaxable): void
    {
        $this->untaxable = $untaxable;
    }

    /**
     * @return float
     */
    public function getTaxPercentage(): float
    {
        return $this->taxPercentage;
    }

    /**
     * @param float|null $taxPercentage
     */
    public function setTaxPercentage(?float $taxPercentage): void
    {
        $this->taxPercentage = $taxPercentage;
    }
}

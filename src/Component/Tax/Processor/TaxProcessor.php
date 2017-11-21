<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Processor;

use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Service\TaxPercentage;
use KejawenLab\Application\SemartHris\Component\Tax\TaxGroup;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class TaxProcessor implements TaxProcessorInterface
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
    public function __construct(PayrollRepositoryInterface $payrollRepository)
    {
        $this->payrollRepository = $payrollRepository;
    }

    /**
     * @param PayrollInterface $payroll
     *
     * @return float
     */
    public function process(PayrollInterface $payroll): float
    {
        $takeHomePay = (float) $payroll->getTakeHomePay();
        $this->untaxable = (float) TaxGroup::$PTKP[$payroll->getEmployee()->getTaxGroup()];
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

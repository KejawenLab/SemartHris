<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Processor;

use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Calculator\InvalidCalculatorException;
use KejawenLab\Application\SemartHris\Component\Tax\Calculator\TaxCalculatorInterface;
use KejawenLab\Application\SemartHris\Component\Tax\TaxGroup;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class TaxProcessor implements TaxProcessorInterface
{
    const SEMARTHRIS_TAX_CALCULATOR = 'semarthris.tax_calculator';

    /**
     * @var PayrollRepositoryInterface
     */
    private $payrollRepository;

    /**
     * @var TaxCalculatorInterface[]
     */
    private $calculators;

    /**
     * @var float
     */
    private $taxable = 0.0;

    /**
     * @var float
     */
    private $untaxable = 0.0;

    /**
     * @param PayrollRepositoryInterface $payrollRepository
     * @param TaxCalculatorInterface[]   $calculators
     */
    public function __construct(PayrollRepositoryInterface $payrollRepository, array $calculators = [])
    {
        $this->payrollRepository = $payrollRepository;
        foreach ($calculators as $calculator) {
            $this->addCalculator($calculator);
        }
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

        foreach ($this->calculators as $calculator) {
            if ($calculator->isSupportPkp($this->taxable)) {
                return round($calculator->calculate($this->taxable) / 12, 0, PHP_ROUND_HALF_DOWN);
            }
        }

        throw new InvalidCalculatorException();
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
     * @param TaxCalculatorInterface $calculator
     */
    private function addCalculator(TaxCalculatorInterface $calculator)
    {
        $this->calculators[] = $calculator;
    }
}

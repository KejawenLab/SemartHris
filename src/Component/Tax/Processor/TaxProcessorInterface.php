<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Processor;

use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface TaxProcessorInterface
{
    /**
     * @param PayrollInterface $payroll
     *
     * @return float
     */
    public function process(PayrollInterface $payroll): float;

    /**
     * @return float
     */
    public function getTaxableValue(): float;

    /**
     * @return float
     */
    public function getUntaxableValue(): float;
}

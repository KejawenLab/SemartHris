<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface TaxProcessorInterface
{
    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return float
     */
    public function process(EmployeeInterface $employee, PayrollPeriodInterface $period): float;

    /**
     * @return float
     */
    public function getTaxableValue(): float;

    /**
     * @return float
     */
    public function getUntaxableValue(): float;

    /**
     * @return float
     */
    public function getTaxPercentage(): float;
}

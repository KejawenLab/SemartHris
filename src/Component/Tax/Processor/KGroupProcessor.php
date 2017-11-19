<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;
use KejawenLab\Application\SemartHris\Component\Tax\TaxGroup;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class KGroupProcessor extends Processor
{
    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return float
     */
    public function process(EmployeeInterface $employee, PayrollPeriodInterface $period): float
    {
        if (!TaxGroup::isKGroup($employee->getTaxGroup())) {
            throw new TaxProcessorException();
        }

        return $this->calculate($employee, $period);
    }
}

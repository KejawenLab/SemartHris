<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface SalaryProcessorInterface
{
    /**
     * @param PayrollInterface   $payroll
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     * @param float              $fixedSalary
     *
     * @return float
     */
    public function process(PayrollInterface $payroll, EmployeeInterface $employee, \DateTimeInterface $date, float $fixedSalary): float;
}

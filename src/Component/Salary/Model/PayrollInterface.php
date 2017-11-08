<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Model;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface PayrollInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return PayrollPeriodInterface|null
     */
    public function getPeriod(): ? PayrollPeriodInterface;

    /**
     * @param PayrollPeriodInterface|null $period
     */
    public function setPeriod(PayrollPeriodInterface $period = null): void;

    /**
     * @return EmployeeInterface|null
     */
    public function getEmployee(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setEmployee(EmployeeInterface $employee = null): void;
}

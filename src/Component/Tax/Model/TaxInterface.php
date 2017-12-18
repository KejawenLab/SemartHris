<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Model;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface TaxInterface
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
    public function setPeriod(?PayrollPeriodInterface $period): void;

    /**
     * @return EmployeeInterface|null
     */
    public function getEmployee(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setEmployee(?EmployeeInterface $employee): void;

    /**
     * @return null|string
     */
    public function getTaxGroup(): ? string;

    /**
     * @param null|string $taxGroup
     */
    public function setTaxGroup(?string $taxGroup): void;

    /**
     * @return null|string
     */
    public function getUntaxable(): ? string;

    /**
     * @param string $untaxable
     */
    public function setUntaxable(string $untaxable): void;

    /**
     * @return null|string
     */
    public function getTaxable(): ? string;

    /**
     * @param string $taxable
     */
    public function setTaxable(string $taxable): void;

    /**
     * @return null|string
     */
    public function getTaxValue(): ? string;

    /**
     * @param string $taxValue
     */
    public function setTaxValue(string $taxValue): void;

    /**
     * @return null|string
     */
    public function getTaxKey(): ? string;
}

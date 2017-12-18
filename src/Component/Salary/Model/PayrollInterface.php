<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Model;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
    public function getTakeHomePay(): ? string;

    /**
     * @param string $takeHomePay
     */
    public function setTakeHomePay(string $takeHomePay): void;

    /**
     * @return null|string
     */
    public function getTakeHomePayKey(): ? string;

    public function close(): void;
}

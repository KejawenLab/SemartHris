<?php

namespace KejawenLab\Application\SemartHris\Component\Overtime\Model;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface OvertimeInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return EmployeeInterface|null
     */
    public function getEmployee(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface $employee
     */
    public function setEmployee(EmployeeInterface $employee): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getOvertimeDate(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface $date
     */
    public function setOvertimeDate(\DateTimeInterface $date): void;

    /**
     * @return float
     */
    public function getOvertimeValue(): float;

    /**
     * @return float|null
     */
    public function getCalculatedValue(): ? float;

    /**
     * @param float $overtime
     */
    public function setCalculatedValue(float $overtime): void;

    /**
     * @return bool
     */
    public function isHoliday(): bool;

    /**
     * @return bool
     */
    public function isApproved(): bool;

    /**
     * @return null|string
     */
    public function getDescription(): ? string;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void;
}

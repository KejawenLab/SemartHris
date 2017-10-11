<?php

namespace KejawenLab\Application\SemarHris\Component\Employee\Model;

use KejawenLab\Application\SemarHris\Component\Employee\ContractType;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
interface ContractInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return null|EmployeeInterface
     */
    public function getEmployee(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface $employee
     */
    public function setEmployee(EmployeeInterface $employee = null): void;

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime;

    /**
     * @return \DateTime|null
     */
    public function getEndDate(): ? \DateTime;

    /**
     * @return string
     */
    public function getLetterNumber(): string;

    /**
     * @return string
     *
     * @see ContractType
     */
    public function getType(): string;

    /**
     * @return bool
     */
    public function isActive(): bool;
}

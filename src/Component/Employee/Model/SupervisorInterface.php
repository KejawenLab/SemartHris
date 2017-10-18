<?php

namespace KejawenLab\Application\SemartHris\Component\Employee\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface SupervisorInterface
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
     * @return null|EmployeeInterface
     */
    public function getSupervisor(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface $supervisor
     */
    public function setSupervisor(EmployeeInterface $supervisor = null): void;

    /**
     * @return \DateTimeInterface
     */
    public function getStartDate(): \DateTimeInterface;

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ? \DateTimeInterface;

    /**
     * @return bool
     */
    public function isActive(): bool;
}

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
     * @return \DateTime
     */
    public function getStartDate(): \DateTime;

    /**
     * @return \DateTime|null
     */
    public function getEndDate(): ? \DateTime;

    /**
     * @return bool
     */
    public function isActive(): bool;
}

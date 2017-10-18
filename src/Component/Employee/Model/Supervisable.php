<?php

namespace KejawenLab\Application\SemartHris\Component\Employee\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface Supervisable
{
    /**
     * @return EmployeeInterface|null
     */
    public function getSupervisor(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $supervisor
     */
    public function setSupervisor(EmployeeInterface $supervisor = null): void;

    /**
     * @return string
     */
    public function getSupervisorClass(): string;
}

<?php

namespace KejawenLab\Application\SemartHris\Component\Employee\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface Superviseable extends EmployeeInterface
{
    /**
     * @return null|Superviseable
     */
    public function getSupervisor(): ? Superviseable;

    /**
     * @param Superviseable|null $superviseable
     */
    public function setSupervisor(?Superviseable $superviseable): void;
}

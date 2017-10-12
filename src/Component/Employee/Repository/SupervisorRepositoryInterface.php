<?php

namespace KejawenLab\Application\SemarHris\Component\Employee\Repository;

use KejawenLab\Application\SemarHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface SupervisorRepositoryInterface
{
    /**
     * @param EmployeeInterface $employee
     *
     * @return null|EmployeeInterface
     */
    public function findByEmployee(EmployeeInterface $employee): ? EmployeeInterface;
}

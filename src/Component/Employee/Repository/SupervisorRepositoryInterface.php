<?php

namespace Persona\Hris\Component\Employee\Repository;

use Persona\Hris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
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

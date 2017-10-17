<?php

namespace KejawenLab\Application\SemartHris\Component\Employee\Model;

use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface EmployeeContractInterface extends ContractInterface
{
    /**
     * @return null|EmployeeInterface
     */
    public function getEmployee(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setEmployee(EmployeeInterface $employee = null): void;
}

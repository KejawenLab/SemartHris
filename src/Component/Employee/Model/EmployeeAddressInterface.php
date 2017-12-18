<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Employee\Model;

use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface EmployeeAddressInterface extends AddressInterface
{
    /**
     * @return null|EmployeeInterface
     */
    public function getEmployee(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setEmployee(?EmployeeInterface $employee): void;
}

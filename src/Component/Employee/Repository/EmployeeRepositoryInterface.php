<?php

namespace KejawenLab\Application\SemartHris\Component\Employee\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface EmployeeRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return EmployeeInterface
     */
    public function find(string $id): ? EmployeeInterface;
}

<?php

namespace KejawenLab\Application\SemartHris\Component\Company\Repository;

use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface DepartmentRepositoryInterface
{
    /**
     * @param string $companyId
     *
     * @return DepartmentInterface[]
     */
    public function findByCompany(string $companyId): array;

    /**
     * @param string $id
     *
     * @return DepartmentInterface
     */
    public function find(string $id): ? DepartmentInterface;
}

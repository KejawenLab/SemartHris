<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Repository;

use KejawenLab\Application\SemartHris\Component\Salary\Model\ComponentInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface ComponentRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return ComponentInterface|null
     */
    public function find(string $id): ? ComponentInterface;
}

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

    /**
     * @param string $code
     *
     * @return ComponentInterface|null
     */
    public function findByCode(string $code): ? ComponentInterface;

    /**
     * @param string $state
     *
     * @return ComponentInterface[]
     */
    public function findByState(string $state): array;
}

<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Salary\Model\ComponentInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\ComponentRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SalaryComponentRepository extends Repository implements ComponentRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return null|ComponentInterface
     */
    public function find(string $id): ? ComponentInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->find($id);
    }

    /**
     * @param string $code
     *
     * @return ComponentInterface|null
     */
    public function findByCode(string $code): ? ComponentInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy(['code' => $code]);
    }
}

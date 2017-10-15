<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;
use KejawenLab\Application\SemartHris\Component\Job\Repository\JobLevelRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class JobLevelRepository extends Repository implements JobLevelRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return JobLevelInterface
     */
    public function find(string $id): ? JobLevelInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->find($id);
    }
}

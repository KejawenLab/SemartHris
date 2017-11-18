<?php

namespace KejawenLab\Application\SemartHris\Component\Job\Repository;

use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface JobLevelRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return JobLevelInterface
     */
    public function find(?string $id): ? JobLevelInterface;
}

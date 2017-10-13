<?php

namespace KejawenLab\Application\SemartHris\Component\Employee\Repository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface SupervisorRepositoryInterface
{
    /**
     * @param string $jobLevelId
     *
     * @return array
     */
    public function findSupervisorByJobLevel(string $jobLevelId): array;
}

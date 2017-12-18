<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Job\Repository;

use KejawenLab\Application\SemartHris\Component\Job\Model\JobTitleInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface JobTitleRepositoryInterface
{
    /**
     * @param string $jobLevelId
     *
     * @return JobTitleInterface[]
     */
    public function findByJobLevel(string $jobLevelId): array;

    /**
     * @param string $id
     *
     * @return JobTitleInterface
     */
    public function find(?string $id): ? JobTitleInterface;
}

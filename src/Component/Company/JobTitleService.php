<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Company;

use KejawenLab\Semart\Skeleton\Component\Contract\Company\JobTitleInterface;
use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\JobTitle;
use KejawenLab\Semart\Skeleton\Repository\JobTitleRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class JobTitleService implements ServiceInterface
{
    private $jobRepository;

    public function __construct(JobTitleRepository $jobRepository)
    {
        $jobRepository->setCacheable(true);
        $this->jobRepository = $jobRepository;
    }

    /**
     * @param string $id
     *
     * @return JobTitle|null
     */
    public function get(string $id): ?object
    {
        return $this->jobRepository->find($id);
    }

    /**
     * @param int $level
     *
     * @return JobTitle[]
     */
    public function getSupervisors(int $level): array
    {
        return $this->jobRepository->findSupervisors($level);
    }

    public static function getLevels(): array
    {
        return [
            JobTitleInterface::LEVEL_COMMISSIONER => JobTitleInterface::LEVEL_COMMISSIONER_TEXT,
            JobTitleInterface::LEVEL_DIRECTOR => JobTitleInterface::LEVEL_DIRECTOR_TEXT,
            JobTitleInterface::LEVEL_MANAGER => JobTitleInterface::LEVEL_MANAGER_TEXT,
            JobTitleInterface::LEVEL_SUPERVISOR => JobTitleInterface::LEVEL_SUPERVISOR_TEXT,
            JobTitleInterface::LEVEL_STAFF => JobTitleInterface::LEVEL_STAFF_TEXT,
        ];
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Skill;

use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\Skill;
use KejawenLab\Semart\Skeleton\Repository\SkillRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SkillService implements ServiceInterface
{
    private $skillRepository;

    public function __construct(SkillRepository $skillRepository)
    {
        $skillRepository->setCacheable(true);
        $this->skillRepository = $skillRepository;
    }

    /**
     * @param string $id
     *
     * @return Skill|null
     */
    public function get(string $id): ?object
    {
        return $this->skillRepository->find($id);
    }
}

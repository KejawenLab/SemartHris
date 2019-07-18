<?php
/**
 * This file is part of the Semart HRIS Application.
 *
 * (c) Muhamad Surya Iksanudin <surya.kejawen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Skill;

use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\SkillGroup;
use KejawenLab\Semart\Skeleton\Repository\SkillGroupRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SkillGroupService implements ServiceInterface
{
    private $skillgroupRepository;

    public function __construct(SkillGroupRepository $skillgroupRepository)
    {
        $skillgroupRepository->setCacheable(true);
        $this->skillgroupRepository = $skillgroupRepository;
    }

    /**
     * @return SkillGroup[]
     */
    public function getAll(): array
    {
        return $this->skillgroupRepository->findAll();
    }

    /**
     * @param string $id
     *
     * @return SkillGroup|null
     */
    public function get(string $id): ?object
    {
        return $this->skillgroupRepository->find($id);
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Company;

use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\CompanyGroup;
use KejawenLab\Semart\Skeleton\Repository\CompanyGroupRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyGroupService implements ServiceInterface
{
    private $companygroupRepository;

    public function __construct(CompanyGroupRepository $companygroupRepository)
    {
        $companygroupRepository->setCacheable(true);
        $this->companygroupRepository = $companygroupRepository;
    }

    /**
     * @param string $id
     *
     * @return CompanyGroup|null
     */
    public function get(string $id): ?object
    {
        return $this->companygroupRepository->find($id);
    }
}

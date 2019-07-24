<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Organization;

use KejawenLab\Semart\Skeleton\Component\Contract\Organization\OrganizationInterface;
use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\Organization;
use KejawenLab\Semart\Skeleton\Repository\OrganizationRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OrganizationService implements ServiceInterface
{
    private $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        $organizationRepository->setCacheable(true);
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * @param string $id
     *
     * @return Organization|null
     */
    public function get(string $id): ?object
    {
        return $this->organizationRepository->find($id);
    }

    /**
     * @param int $level
     *
     * @return Organization[]
     */
    public function getByLevel(int $level): array
    {
        return $this->organizationRepository->findBy(['level' => $level]);
    }

    public static function convertLevelToText(int $level): string
    {
        switch ($level) {
            case OrganizationInterface::LEVEL_DIVISION:
                return 'divisi';
                break;
            case OrganizationInterface::LEVEL_DEPARTMENT:
                return 'departemen';
                break;
            case OrganizationInterface::LEVEL_SECTION:
                return 'seksi';
                break;
            default:
                return 'sub seksi';
        }
    }
}

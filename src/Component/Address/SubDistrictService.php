<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Address;

use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\SubDistrict;
use KejawenLab\Semart\Skeleton\Repository\SubDistrictRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SubDistrictService implements ServiceInterface
{
    private $subdistrictRepository;

    public function __construct(SubDistrictRepository $subdistrictRepository)
    {
        $subdistrictRepository->setCacheable(true);
        $this->subdistrictRepository = $subdistrictRepository;
    }

    /**
     * @param string $id
     *
     * @return SubDistrict|null
     */
    public function get(string $id): ?object
    {
        return $this->subdistrictRepository->find($id);
    }
}

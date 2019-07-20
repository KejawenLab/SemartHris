<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Address;

use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\Province;
use KejawenLab\Semart\Skeleton\Repository\ProvinceRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ProvinceService implements ServiceInterface
{
    private $provinceRepository;

    public function __construct(ProvinceRepository $provinceRepository)
    {
        $provinceRepository->setCacheable(true);
        $this->provinceRepository = $provinceRepository;
    }

    /**
     * @param string $id
     *
     * @return Province|null
     */
    public function get(string $id): ?object
    {
        return $this->provinceRepository->find($id);
    }

    /**
     * @return Province[]
     */
    public function getAll(): array
    {
        return $this->provinceRepository->findAll();
    }
}

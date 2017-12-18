<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Address\Repository;

use KejawenLab\Application\SemartHris\Component\Address\Model\CityInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface CityRepositoryInterface
{
    /**
     * @param string $regionId
     *
     * @return CityInterface[]
     */
    public function findCityByRegion(string $regionId): array;

    /**
     * @param string $id
     *
     * @return null|CityInterface
     */
    public function find(?string $id): ? CityInterface;
}

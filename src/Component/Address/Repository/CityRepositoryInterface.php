<?php

namespace Persona\Hris\Component\Address\Repository;

use Persona\Hris\Component\Address\Model\CityInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
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
    public function find(string $id): ? CityInterface;
}

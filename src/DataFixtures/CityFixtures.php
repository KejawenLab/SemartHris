<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use KejawenLab\Application\SemartHris\Entity\City;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CityFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @return array
     */
    public function getDependencies()
    {
        return [RegionFixtures::class];
    }

    /**
     * @return string
     */
    protected function getFixtureFilePath(): string
    {
        return 'city.yaml';
    }

    /**
     * @return mixed
     */
    protected function createNew()
    {
        return new City();
    }

    /**
     * @return string
     */
    protected function getReferenceKey(): string
    {
        return 'city';
    }
}

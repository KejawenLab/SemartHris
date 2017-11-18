<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DataFixtures\ORM;

use KejawenLab\Application\SemartHris\Entity\Region;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class RegionFixtures extends Fixture
{
    /**
     * @return string
     */
    protected function getFixtureFilePath(): string
    {
        return 'region.yaml';
    }

    /**
     * @return mixed
     */
    protected function createNew()
    {
        return new Region();
    }

    /**
     * @return string
     */
    protected function getReferenceKey(): string
    {
        return 'region';
    }
}

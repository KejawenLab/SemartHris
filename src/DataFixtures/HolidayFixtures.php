<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DataFixtures;

use KejawenLab\Application\SemartHris\Entity\Holiday;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class HolidayFixtures extends Fixture
{
    /**
     * @return string
     */
    protected function getFixtureFilePath(): string
    {
        return 'holiday.yaml';
    }

    /**
     * @return mixed
     */
    protected function createNew()
    {
        return new Holiday();
    }

    /**
     * @return string
     */
    protected function getReferenceKey(): string
    {
        return 'holiday';
    }
}

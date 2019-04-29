<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DataFixtures;

use KejawenLab\Application\SemartHris\Entity\Shiftment;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ShiftmentFixtures extends Fixture
{
    /**
     * @return string
     */
    protected function getFixtureFilePath(): string
    {
        return 'shiftment.yaml';
    }

    /**
     * @return mixed
     */
    protected function createNew()
    {
        return new Shiftment();
    }

    /**
     * @return string
     */
    protected function getReferenceKey(): string
    {
        return 'shiftment';
    }
}

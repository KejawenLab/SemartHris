<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DataFixtures;

use KejawenLab\Application\SemartHris\Entity\Reason;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ReasonFixtures extends Fixture
{
    /**
     * @return string
     */
    protected function getFixtureFilePath(): string
    {
        return 'reason.yaml';
    }

    /**
     * @return mixed
     */
    protected function createNew()
    {
        return new Reason();
    }

    /**
     * @return string
     */
    protected function getReferenceKey(): string
    {
        return 'reason';
    }
}

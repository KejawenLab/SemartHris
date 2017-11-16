<?php

namespace KejawenLab\Application\SemartHris\DataFixtures\ORM;

use KejawenLab\Application\SemartHris\Entity\Reason;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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

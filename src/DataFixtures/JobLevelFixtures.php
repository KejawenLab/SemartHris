<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DataFixtures;

use KejawenLab\Application\SemartHris\Entity\JobLevel;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class JobLevelFixtures extends Fixture
{
    /**
     * @return string
     */
    protected function getFixtureFilePath(): string
    {
        return 'job_level.yaml';
    }

    /**
     * @return mixed
     */
    protected function createNew()
    {
        return new JobLevel();
    }

    /**
     * @return string
     */
    protected function getReferenceKey(): string
    {
        return 'job_level';
    }
}

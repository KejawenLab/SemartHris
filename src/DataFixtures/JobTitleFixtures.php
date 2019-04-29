<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use KejawenLab\Application\SemartHris\Entity\JobTitle;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class JobTitleFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @return array
     */
    public function getDependencies()
    {
        return [JobLevelFixtures::class];
    }

    /**
     * @return string
     */
    protected function getFixtureFilePath(): string
    {
        return 'job_title.yaml';
    }

    /**
     * @return mixed
     */
    protected function createNew()
    {
        return new JobTitle();
    }

    /**
     * @return string
     */
    protected function getReferenceKey(): string
    {
        return 'job_title';
    }
}

<?php

namespace KejawenLab\Application\SemartHris\DataFixtures\ORM;

use KejawenLab\Application\SemartHris\Entity\EducationalInstitute;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
class EducationalInstituteFixtures extends Fixture
{
    /**
     * @return string
     */
    protected function getFixtureFilePath(): string
    {
        return 'education_institute.yaml';
    }

    /**
     * @return mixed
     */
    protected function createNew()
    {
        return new EducationalInstitute();
    }

    /**
     * @return string
     */
    protected function getReferenceKey(): string
    {
        return 'educational_institute';
    }
}

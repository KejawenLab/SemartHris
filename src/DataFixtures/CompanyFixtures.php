<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DataFixtures;

use KejawenLab\Application\SemartHris\Entity\Company;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyFixtures extends Fixture
{
    /**
     * @return string
     */
    protected function getFixtureFilePath(): string
    {
        return 'company.yaml';
    }

    /**
     * @return mixed
     */
    protected function createNew()
    {
        return new Company();
    }

    /**
     * @return string
     */
    protected function getReferenceKey(): string
    {
        return 'company';
    }
}

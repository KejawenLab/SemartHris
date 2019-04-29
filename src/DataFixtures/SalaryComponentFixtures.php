<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DataFixtures;

use KejawenLab\Application\SemartHris\Entity\SalaryComponent;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SalaryComponentFixtures extends Fixture
{
    /**
     * @return string
     */
    protected function getFixtureFilePath(): string
    {
        return 'salary_component.yaml';
    }

    /**
     * @return mixed
     */
    protected function createNew()
    {
        return new SalaryComponent();
    }

    /**
     * @return string
     */
    protected function getReferenceKey(): string
    {
        return 'salary_component';
    }
}

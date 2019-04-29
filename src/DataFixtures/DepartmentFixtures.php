<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DataFixtures;

use KejawenLab\Application\SemartHris\Entity\Department;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class DepartmentFixtures extends Fixture
{
    /**
     * @return string
     */
    protected function getFixtureFilePath(): string
    {
        return 'department.yaml';
    }

    /**
     * @return mixed
     */
    protected function createNew()
    {
        return new Department();
    }

    /**
     * @return string
     */
    protected function getReferenceKey(): string
    {
        return 'department';
    }
}

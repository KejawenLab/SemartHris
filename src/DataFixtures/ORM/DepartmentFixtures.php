<?php

namespace KejawenLab\Application\SemartHris\DataFixtures\ORM;

use KejawenLab\Application\SemartHris\Entity\Department;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
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

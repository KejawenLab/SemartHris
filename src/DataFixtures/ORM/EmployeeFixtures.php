<?php

namespace KejawenLab\Application\SemartHris\DataFixtures\ORM;

use KejawenLab\Application\SemartHris\Entity\Employee;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
class EmployeeFixtures extends Fixture
{
    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            CompanyFixtures::class,
            DepartmentFixtures::class,
            CompanyDepartmentFixtures::class,
            RegionFixtures::class,
            CityFixtures::class,
            ContractFixtures::class,
            JobLevelFixtures::class,
            JobTitleFixtures::class,
        ];
    }

    /**
     * @return string
     */
    protected function getFixtureFilePath(): string
    {
        return 'employee.yaml';
    }

    /**
     * @return mixed
     */
    protected function createNew()
    {
        return new Employee();
    }

    /**
     * @return string
     */
    protected function getReferenceKey(): string
    {
        return 'employee';
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use KejawenLab\Application\SemartHris\Entity\Employee;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EmployeeFixtures extends Fixture implements DependentFixtureInterface
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

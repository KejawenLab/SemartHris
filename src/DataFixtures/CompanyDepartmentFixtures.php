<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use KejawenLab\Application\SemartHris\Entity\CompanyDepartment;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyDepartmentFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @return array
     */
    public function getDependencies()
    {
        return [CompanyFixtures::class, DepartmentFixtures::class];
    }

    /**
     * @return string
     */
    protected function getFixtureFilePath(): string
    {
        return 'company_department.yaml';
    }

    /**
     * @return mixed
     */
    protected function createNew()
    {
        return new CompanyDepartment();
    }

    /**
     * @return string
     */
    protected function getReferenceKey(): string
    {
        return 'company_department';
    }
}

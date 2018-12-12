<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Job\Service;

use KejawenLab\Application\SemartHris\Component\Employee\Model\Superviseable;
use KejawenLab\Application\SemartHris\Component\Job\Model\MutationInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SetEmployeeNewJob
{
    /**
     * @param MutationInterface $mutation
     */
    public static function setNewJob(MutationInterface $mutation): void
    {
        /** @var Superviseable $employee */
        $employee = $mutation->getEmployee();
        $company = $mutation->getNewCompany() ?? $employee->getCompany();
        $department = $mutation->getNewDepartment() ?? $employee->getDepartment();
        $jobLevel = $mutation->getNewJobLevel() ?? $employee->getJobLevel();
        $jobTitle = $mutation->getNewJobTitle() ?? $employee->getJobTitle();
        $supervisor = $mutation->getNewSupervisor() ?? $employee->getSupervisor();

        $employee->setCompany($company);
        $employee->setDepartment($department);
        $employee->setJobLevel($jobLevel);
        $employee->setJobTitle($jobTitle);
        $employee->setSupervisor($supervisor);
    }
}

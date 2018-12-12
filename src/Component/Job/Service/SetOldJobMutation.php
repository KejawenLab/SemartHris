<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Job\Service;

use KejawenLab\Application\SemartHris\Component\Employee\Model\Superviseable;
use KejawenLab\Application\SemartHris\Component\Job\Model\MutationInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SetOldJobMutation
{
    /**
     * @param MutationInterface $mutation
     */
    public static function setOldJob(MutationInterface $mutation): void
    {
        /** @var Superviseable $employee */
        $employee = $mutation->getEmployee();
        $mutation->setOldCompany($employee->getCompany());
        $mutation->setOldDepartment($employee->getDepartment());
        $mutation->setOldJobLevel($employee->getJobLevel());
        $mutation->setOldJobTitle($employee->getJobTitle());
        $mutation->setOldSupervisor($employee->getSupervisor());
    }
}

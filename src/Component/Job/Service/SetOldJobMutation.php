<?php

namespace KejawenLab\Application\SemartHris\Component\Job\Service;

use KejawenLab\Application\SemartHris\Component\Job\Model\MutationInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class SetOldJobMutation
{
    /**
     * @param MutationInterface $mutation
     */
    public static function setOldJob(MutationInterface $mutation): void
    {
        $employee = $mutation->getEmployee();
        $mutation->setOldCompany($employee->getCompany());
        $mutation->setOldDepartment($employee->getDepartment());
        $mutation->setOldJobLevel($employee->getJobLevel());
        $mutation->setOldJobTitle($employee->getJobTitle());
        $mutation->setOldSupervisor($employee->getSupervisor());
    }
}

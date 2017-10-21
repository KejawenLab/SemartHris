<?php

namespace KejawenLab\Application\SemartHris\Component\Job\Service;

use KejawenLab\Application\SemartHris\Component\Job\Model\MutationInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class ValidateMutationService
{
    /**
     * @param MutationInterface $mutation
     *
     * @return bool
     */
    public static function validate(MutationInterface $mutation): bool
    {
        $count = 0;
        $employee = $mutation->getEmployee();

        if (null === $mutation->getOldCompany() || $mutation->getOldCompany() === $employee->getCompany()) {
            ++$count;
        }

        if (null === $mutation->getOldDepartment() || $mutation->getOldDepartment() === $employee->getDepartment()) {
            ++$count;
        }

        if (null === $mutation->getOldJobLevel() || $mutation->getOldJobLevel() === $employee->getJobLevel()) {
            ++$count;
        }

        if (null === $mutation->getOldJobTitle() || $mutation->getOldJobTitle() === $employee->getJobTitle()) {
            ++$count;
        }

        if (null === $mutation->getOldSupervisor() || $mutation->getOldSupervisor() === $employee->getSupervisor()) {
            ++$count;
        }

        if (5 === $count) {
            return false;
        }

        return true;
    }
}

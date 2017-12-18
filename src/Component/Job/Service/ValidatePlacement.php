<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Job\Service;

use KejawenLab\Application\SemartHris\Component\Job\Model\PlacementInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidatePlacement
{
    /**
     * @param PlacementInterface $placement
     *
     * @return bool
     */
    public static function validate(PlacementInterface $placement): bool
    {
        $count = 0;
        $employee = $placement->getEmployee();

        if (null === $placement->getCompany() || $placement->getCompany() === $employee->getCompany()) {
            ++$count;
        }

        if (null === $placement->getDepartment() || $placement->getDepartment() === $employee->getDepartment()) {
            ++$count;
        }

        if (null === $placement->getJobLevel() || $placement->getJobLevel() === $employee->getJobLevel()) {
            ++$count;
        }

        if (null === $placement->getJobTitle() || $placement->getJobTitle() === $employee->getJobTitle()) {
            ++$count;
        }

        if (null === $placement->getSupervisor() || $placement->getSupervisor() === $employee->getSupervisor()) {
            ++$count;
        }

        if (5 === $count) {
            return false;
        }

        return true;
    }
}

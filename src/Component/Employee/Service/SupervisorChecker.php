<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Employee\Service;

use KejawenLab\Application\SemartHris\Component\Employee\Model\Superviseable;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SupervisorChecker
{
    /**
     * @param Superviseable $employee
     * @param Superviseable $supervisor
     *
     * @return bool
     */
    public function isAllowToSupervise(Superviseable $employee, Superviseable $supervisor): bool
    {
        $employeeJobTitle = $employee->getJobTitle();
        $supervisorJobTitle = $supervisor->getJobTitle();

        if (!$employeeJobTitle || !$supervisorJobTitle) {
            return false;
        }

        if ($employeeJobTitle->getJobLevel()->getId() === $supervisorJobTitle->getJobLevel()->getId()) {
            return false;
        }

        return $this->canSupervise($employee, $supervisor);
    }

    /**
     * @param Superviseable $employee
     * @param Superviseable $supervisor
     *
     * @return bool
     */
    private function canSupervise(Superviseable $employee, Superviseable $supervisor): bool
    {
        $allow = false;

        if ($employeeSupervisor = $employee->getSupervisor()) {
            if ($employeeSupervisor->getId() === $supervisor->getId()) {
                $allow = true;
            } else {
                $this->canSupervise($employeeSupervisor, $supervisor);
            }
        }

        return $allow;
    }
}

<?php

namespace KejawenLab\Application\SemartHris\Component\Employee\Service;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class EmployeeSupervisorChecker
{
    /**
     * @param EmployeeInterface $employee
     * @param EmployeeInterface $supervisor
     *
     * @return bool
     */
    public function isAllowToSupervise(EmployeeInterface $employee, EmployeeInterface $supervisor): bool
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
     * @param EmployeeInterface $employee
     * @param EmployeeInterface $supervisor
     *
     * @return bool
     */
    private function canSupervise(EmployeeInterface $employee, EmployeeInterface $supervisor): bool
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

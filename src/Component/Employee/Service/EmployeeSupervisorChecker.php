<?php

namespace KejawenLab\Application\SemarHris\Component\Employee\Service;

use KejawenLab\Application\SemarHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemarHris\Component\Employee\Repository\SupervisorRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class EmployeeSupervisorChecker
{
    /**
     * @var SupervisorRepositoryInterface
     */
    private $supervisorRepository;

    /**
     * @param SupervisorRepositoryInterface $supervisorRepository
     */
    public function __construct(SupervisorRepositoryInterface $supervisorRepository)
    {
        $this->supervisorRepository = $supervisorRepository;
    }

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

        $employeeSupervisor = $this->supervisorRepository->findByEmployee($employee);
        if ($employeeSupervisor) {
            if ($employeeSupervisor->getId() === $supervisor->getId()) {
                $allow = true;
            } else {
                $this->canSupervise($employeeSupervisor, $supervisor);
            }
        }

        return $allow;
    }
}

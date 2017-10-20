<?php

namespace KejawenLab\Application\SemartHris\Component\Job\Service;

use KejawenLab\Application\SemartHris\Component\Contract\Model\Contractable;
use KejawenLab\Application\SemartHris\Component\Contract\Service\ValidateMutaionType;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\CareerHistoryable;
use KejawenLab\Application\SemartHris\Component\Job\Model\CareerHistoryInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\PlacementInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\MutationInterface;
use KejawenLab\Application\SemartHris\Component\Job\Repository\CareerHistoryRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class AddCareerHistoryService
{
    /**
     * @var CareerHistoryRepositoryInterface
     */
    private $careerHistoryRepository;

    /**
     * @var string
     */
    private $careerHistoryClass;

    /**
     * @param CareerHistoryRepositoryInterface $repository
     * @param string                           $careerHistoryClass
     */
    public function __construct(CareerHistoryRepositoryInterface $repository, string $careerHistoryClass)
    {
        $this->careerHistoryRepository = $repository;
        $this->careerHistoryClass = $careerHistoryClass;
    }

    /**
     * @param CareerHistoryable $careerHistoryable
     */
    public function store(CareerHistoryable $careerHistoryable)
    {
        /** @var CareerHistoryInterface $careerHistory */
        $careerHistory = new $this->careerHistoryClass();

        if ($careerHistoryable instanceof PlacementInterface) {
            $careerHistory->setEmployee($careerHistoryable->getEmployee());
            $careerHistory->setSupervisor($careerHistoryable->getSupervisor());
            $careerHistory->setCompany($careerHistoryable->getCompany());
            $careerHistory->setDepartment($careerHistoryable->getDepartment());
            $careerHistory->setJobLevel($careerHistoryable->getJobLevel());
            $careerHistory->setJobTitle($careerHistoryable->getJobTitle());
            $careerHistory->setContract($careerHistoryable->getContract());
            $careerHistory->setDescription('PENEMPATAN');
        }

        if ($careerHistoryable instanceof MutationInterface) {
            /** @var Contractable|EmployeeInterface $employee */
            $employee = $careerHistoryable->getEmployee();
            $careerHistory->setEmployee($employee);
            $careerHistory->setSupervisor($careerHistoryable->getCurrentSupervisor());
            $careerHistory->setCompany($careerHistoryable->getCurrentCompany());
            $careerHistory->setDepartment($careerHistoryable->getCurrentDepartment());
            $careerHistory->setJobLevel($careerHistoryable->getCurrentJobLevel());
            $careerHistory->setJobTitle($careerHistoryable->getCurrentJobTitle());
            $careerHistory->setContract($employee->getContract());
            $careerHistory->setDescription(ValidateMutaionType::convertToText($careerHistoryable->getType()));
        }

        $this->careerHistoryRepository->storeHistory($careerHistory);
    }
}

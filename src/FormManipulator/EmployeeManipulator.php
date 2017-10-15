<?php

namespace KejawenLab\Application\SemartHris\FormManipulator;

use KejawenLab\Application\SemartHris\Component\Address\Repository\CityRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Company\Repository\DepartmentRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Repository\EmployeeRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Job\Repository\JobTitleRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class EmployeeManipulator implements FormManipulatorInterface
{
    /**
     * @var DataTransformerInterface
     */
    private $departmentTransformer;

    /**
     * @var DataTransformerInterface
     */
    private $jobLevelTransformer;

    /**
     * @var DataTransformerInterface
     */
    private $jobTitleTransformer;

    /**
     * @var DataTransformerInterface
     */
    private $employeeTransformer;

    /**
     * @var CityRepositoryInterface
     */
    private $cityRepository;

    /**
     * @var DepartmentRepositoryInterface
     */
    private $departmentRepository;

    /**
     * @var JobTitleRepositoryInterface
     */
    private $jobTitleRepository;

    /**
     * @var EmployeeRepositoryInterface
     */
    private $employeeRepository;

    /**
     * @param DataTransformerInterface      $departmentTransformer
     * @param DataTransformerInterface      $jobLevelTransformer
     * @param DataTransformerInterface      $jobTitleTransformer
     * @param DataTransformerInterface      $employeeTransformer
     * @param CityRepositoryInterface       $cityRepository
     * @param DepartmentRepositoryInterface $departmentRepository
     * @param JobTitleRepositoryInterface   $jobTitleRepository
     * @param EmployeeRepositoryInterface   $employeeRepository
     */
    public function __construct(
        DataTransformerInterface $departmentTransformer,
        DataTransformerInterface $jobLevelTransformer,
        DataTransformerInterface $jobTitleTransformer,
        DataTransformerInterface $employeeTransformer,
        CityRepositoryInterface $cityRepository,
        DepartmentRepositoryInterface $departmentRepository,
        JobTitleRepositoryInterface $jobTitleRepository,
        EmployeeRepositoryInterface $employeeRepository
    ) {
        $this->departmentTransformer = $departmentTransformer;
        $this->jobLevelTransformer = $jobLevelTransformer;
        $this->jobTitleTransformer = $jobTitleTransformer;
        $this->employeeTransformer = $employeeTransformer;
        $this->cityRepository = $cityRepository;
        $this->departmentRepository = $departmentRepository;
        $this->jobTitleRepository = $jobTitleRepository;
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * @param FormBuilderInterface $formBuilder
     * @param mixed                $entity
     *
     * @return FormBuilderInterface
     */
    public function manipulate(FormBuilderInterface $formBuilder, $entity): FormBuilderInterface
    {/** @var EmployeeInterface $entity */
        if (!$companyEntity = $entity->getCompany()) {
            $formBuilder->remove('company_readonly');
        }

        if (!$departmentEntity = $entity->getDepartment()) {
            $formBuilder->remove('department_readonly');
        }

        if (!$jobLevelEntity = $entity->getJobLevel()) {
            $formBuilder->remove('joblevel_readonly');
        }

        if (!$jobTitleEntity = $entity->getJobTitle()) {
            $formBuilder->remove('jobtitle_readonly');
        }

        if (!$supervisorEntity = $entity->getSupervisor()) {
            $formBuilder->remove('supervisor_readonly');
        }

        return $formBuilder;
    }
}

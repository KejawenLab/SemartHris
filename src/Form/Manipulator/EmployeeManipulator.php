<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\Manipulator;

use KejawenLab\Application\SemartHris\Entity\Employee;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EmployeeManipulator extends FormManipulator implements FormManipulatorInterface
{
    /**
     * @var DataTransformerInterface
     */
    private $companyTransformer;

    /**
     * @var DataTransformerInterface
     */
    private $jobLevelTransformer;

    /**
     * @param DataTransformerInterface $companyTransformer
     * @param DataTransformerInterface $jobLevelTransformer
     * @param array                    $dataTransformers
     * @param array                    $eventSubscribers
     */
    public function __construct(DataTransformerInterface $companyTransformer, DataTransformerInterface $jobLevelTransformer, $dataTransformers = [], $eventSubscribers = [])
    {
        parent::__construct($dataTransformers, $eventSubscribers);
        $this->companyTransformer = $companyTransformer;
        $this->jobLevelTransformer = $jobLevelTransformer;
    }

    /**
     * @param FormBuilderInterface $formBuilder
     * @param mixed                $entity
     *
     * @return FormBuilderInterface
     */
    public function manipulate(FormBuilderInterface $formBuilder, $entity): FormBuilderInterface
    {/* @var Employee $entity */
        $formBuilder = parent::manipulate($formBuilder, $entity);

        if (!$companyEntity = $entity->getCompany()) {
            $formBuilder->remove('company_readonly');
        } else {
            $formBuilder->remove('company');

            $formBuilder->add('company', HiddenType::class);
            $company = $formBuilder->get('company');
            $company->addModelTransformer($this->companyTransformer);
            $company->setData($companyEntity->getId());

            $formBuilder->get('company_readonly')->setData($companyEntity);
        }

        if (!$employeeContract = $entity->getContract()) {
            $formBuilder->remove('contract_readonly');
        } else {
            $formBuilder->remove('contract_text');
            $formBuilder->get('contract_readonly')->setData($employeeContract);
        }

        if (!$entity->getEmployeeStatus()) {
            $formBuilder->remove('employee_status_text');
        } else {
            $formBuilder->remove('employeeStatus');

            $formBuilder->add('employeeStatus', HiddenType::class);
            $company = $formBuilder->get('employeeStatus');
            $company->setData($entity->getEmployeeStatus());

            $formBuilder->get('employee_status_text')->setData($entity->getEmployeeStatusText());
        }

        if (!$departmentEntity = $entity->getDepartment()) {
            $formBuilder->remove('department_readonly');
        } else {
            $formBuilder->remove('department_text');
            $formBuilder->get('department_readonly')->setData($departmentEntity);
        }

        if (!$jobLevelEntity = $entity->getJobLevel()) {
            $formBuilder->remove('joblevel_readonly');
        } else {
            if ($entity->getId()) {
                $formBuilder->remove('jobLevel');

                $formBuilder->add('jobLevel', HiddenType::class);
                $company = $formBuilder->get('jobLevel');
                $company->addModelTransformer($this->jobLevelTransformer);
                $company->setData($jobLevelEntity->getId());

                $formBuilder->get('joblevel_readonly')->setData($jobLevelEntity);
            }
        }

        if (!$jobTitleEntity = $entity->getJobTitle()) {
            $formBuilder->remove('jobtitle_readonly');
        } else {
            $formBuilder->remove('jobtitle_text');
            $formBuilder->get('jobtitle_readonly')->setData($jobTitleEntity);
        }

        if (!$supervisorEntity = $entity->getSupervisor()) {
            if ($entity->getId()) {
                $formBuilder->remove('supervisor_text');
                $formBuilder->remove('supervisor_readonly');
            } else {
                $formBuilder->remove('supervisor_empty');
                $formBuilder->remove('supervisor_readonly');
            }
        } else {
            $formBuilder->remove('supervisor_empty');
            $formBuilder->remove('supervisor_text');
            $formBuilder->get('supervisor_readonly')->setData($supervisorEntity);
        }

        if ($entity->getId()) {
            $formBuilder->remove('riskRatio');
        } else {
            $formBuilder->remove('riskRatio_text');
        }

        if ($entity->getId()) {
            $formBuilder->remove('taxGroup');
        } else {
            $formBuilder->remove('taxGroup_text');
        }

        return $formBuilder;
    }
}

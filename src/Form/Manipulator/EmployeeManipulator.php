<?php

namespace KejawenLab\Application\SemartHris\Form\Manipulator;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class EmployeeManipulator extends FormManipulator implements FormManipulatorInterface
{
    /**
     * @param FormBuilderInterface $formBuilder
     * @param mixed                $entity
     *
     * @return FormBuilderInterface
     */
    public function manipulate(FormBuilderInterface $formBuilder, $entity): FormBuilderInterface
    {/* @var EmployeeInterface $entity */
        $formBuilder = parent::manipulate($formBuilder, $entity);

        if (!$companyEntity = $entity->getCompany()) {
            $formBuilder->remove('company_readonly');
        } else {
            if ($entity->getId()) {
                $formBuilder->remove('company');

                $formBuilder->add('company', HiddenType::class);
                $company = $formBuilder->get('company');
                $company->addModelTransformer($this->getDataTransformerForField('company'));
                $company->setData($companyEntity->getId());

                $formBuilder->get('company_readonly')->setData($companyEntity);
            }
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
                $company->addModelTransformer($this->getDataTransformerForField('jobLevel'));
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

        return $formBuilder;
    }
}

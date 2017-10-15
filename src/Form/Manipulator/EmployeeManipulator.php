<?php

namespace KejawenLab\Application\SemartHris\Form\Manipulator;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
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

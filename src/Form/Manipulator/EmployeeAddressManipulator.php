<?php

namespace KejawenLab\Application\SemartHris\Form\Manipulator;

use KejawenLab\Application\SemartHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class EmployeeAddressManipulator extends FormManipulator implements FormManipulatorInterface
{
    /**
     * @param FormBuilderInterface $formBuilder
     * @param mixed                $entity
     *
     * @return FormBuilderInterface
     */
    public function manipulate(FormBuilderInterface $formBuilder, $entity): FormBuilderInterface
    {
        $formBuilder = parent::manipulate($formBuilder, $entity);

        /** @var EmployeeInterface $employeeEntity */
        if ($employeeEntity = $entity->getEmployee()) {
            $formBuilder->remove('employee');

            $formBuilder->add('employee', HiddenType::class);
            $employee = $formBuilder->get('employee');
            $employee->addModelTransformer($this->getDataTransformerForField('employee'));
            $employee->setData($employeeEntity->getId());

            $formBuilder->get('employee_readonly')->setData($employeeEntity);
        } else {
            $formBuilder->remove('employee_readonly');
        }

        /** @var CityInterface $cityEntity */
        if ($cityEntity = $entity->getCity()) {
            $formBuilder->get('city')->setData($cityEntity->getId());
        }

        return $formBuilder;
    }
}

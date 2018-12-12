<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\Manipulator;

use KejawenLab\Application\SemartHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EmployeeAddressManipulator extends FormManipulator implements FormManipulatorInterface
{
    /**
     * @var DataTransformerInterface
     */
    private $employeeTransformer;

    /**
     * @param DataTransformerInterface $transformer
     * @param array                    $dataTransformers
     * @param array                    $eventSubscribers
     */
    public function __construct(DataTransformerInterface $transformer, $dataTransformers = [], $eventSubscribers = [])
    {
        parent::__construct($dataTransformers, $eventSubscribers);
        $this->employeeTransformer = $transformer;
    }

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
            $employee->addModelTransformer($this->employeeTransformer);
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

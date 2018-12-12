<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\Manipulator;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface FormManipulatorInterface
{
    /**
     * @param FormBuilderInterface $formBuilder
     * @param mixed                $entity
     *
     * @return FormBuilderInterface
     */
    public function manipulate(FormBuilderInterface $formBuilder, $entity): FormBuilderInterface;

    /**
     * @param string                   $field
     * @param DataTransformerInterface $dataTransformer
     */
    public function addDataTransformer(string $field, DataTransformerInterface $dataTransformer): void;

    /**
     * @param EventSubscriberInterface $eventSubscriber
     */
    public function addEventSubscriber(EventSubscriberInterface $eventSubscriber): void;
}

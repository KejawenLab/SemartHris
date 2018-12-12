<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\Manipulator;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class FormManipulator implements FormManipulatorInterface
{
    /**
     * @var DataTransformerInterface[]
     */
    private $dataTransformers = [];

    /**
     * @var EventSubscriberInterface[]
     */
    private $eventSubscribers = [];

    /**
     * @param DataTransformerInterface[] $dataTransformers
     * @param EventSubscriberInterface[] $eventSubscribers
     */
    public function __construct(array $dataTransformers = [], array $eventSubscribers = [])
    {
        foreach ($dataTransformers as $field => $dataTransformer) {
            $this->addDataTransformer($field, $dataTransformer);
        }

        foreach ($eventSubscribers as $eventSubscriber) {
            $this->addEventSubscriber($eventSubscriber);
        }
    }

    /**
     * @param FormBuilderInterface $formBuilder
     * @param mixed                $entity
     *
     * @return FormBuilderInterface
     */
    public function manipulate(FormBuilderInterface $formBuilder, $entity): FormBuilderInterface
    {
        foreach ($this->dataTransformers as $field => $dataTransformer) {
            $formBuilder->get($field)->addModelTransformer($dataTransformer);
        }

        foreach ($this->eventSubscribers as $eventSubscriber) {
            $formBuilder->addEventSubscriber($eventSubscriber);
        }

        return $formBuilder;
    }

    /**
     * @param string                   $field
     * @param DataTransformerInterface $dataTransformer
     */
    public function addDataTransformer(string $field, DataTransformerInterface $dataTransformer): void
    {
        $this->dataTransformers[$field] = $dataTransformer;
    }

    /**
     * @param EventSubscriberInterface $eventSubscriber
     */
    public function addEventSubscriber(EventSubscriberInterface $eventSubscriber): void
    {
        $this->eventSubscribers[] = $eventSubscriber;
    }

    /**
     * @param string $field
     *
     * @return null|DataTransformerInterface
     */
    protected function getDataTransformerForField(string $field): ? DataTransformerInterface
    {
        if (isset($this->dataTransformers[$field])) {
            return $this->dataTransformers[$field];
        }

        return null;
    }
}

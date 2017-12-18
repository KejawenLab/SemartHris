<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\EventListener;

use KejawenLab\Application\SemartHris\Component\Salary\Repository\ComponentRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RemoveBenefitValueFieldSubscriber implements EventSubscriberInterface, FieldRemoverInterface
{
    /**
     * @var ComponentRepositoryInterface
     */
    private $repository;

    /**
     * @param ComponentRepositoryInterface $repository
     */
    public function __construct(ComponentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FormEvent $event
     */
    public function remove(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (isset($data['component'])) {
            $component = $this->repository->find($data['component']);
            if ($component && !$component->isFixed()) {
                $form->remove('benefitValue');
            }
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SUBMIT => 'remove'];
    }
}

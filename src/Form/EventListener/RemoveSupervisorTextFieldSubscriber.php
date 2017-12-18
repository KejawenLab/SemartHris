<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\EventListener;

use KejawenLab\Application\SemartHris\Component\Employee\Repository\EmployeeRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RemoveSupervisorTextFieldSubscriber implements EventSubscriberInterface, FieldRemoverInterface
{
    /**
     * @var EmployeeRepositoryInterface
     */
    private $employeeRepository;

    /**
     * @param EmployeeRepositoryInterface $employeeRepository
     */
    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * @param FormEvent $event
     */
    public function remove(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (isset($data['supervisor']) && $this->employeeRepository->find($data['supervisor'])) {
            $form->remove('supervisor_text');
        }

        if (isset($data['newSupervisor']) && $this->employeeRepository->find($data['newSupervisor'])) {
            $form->remove('supervisor_text');
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

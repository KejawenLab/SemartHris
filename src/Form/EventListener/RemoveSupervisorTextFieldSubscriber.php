<?php

namespace KejawenLab\Application\SemartHris\Form\EventListener;

use KejawenLab\Application\SemartHris\Component\Employee\Repository\EmployeeRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class RemoveSupervisorTextFieldSubscriber implements EventSubscriberInterface
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
    public function removeSupervisorText(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if ($this->employeeRepository->find($data['supervisor'])) {
            $form->remove('supervisor_text');
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [FormEvents::PRE_SUBMIT => 'removeSupervisorText'];
    }
}

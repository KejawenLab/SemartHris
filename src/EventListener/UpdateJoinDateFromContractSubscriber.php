<?php

namespace KejawenLab\Application\SemartHris\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use KejawenLab\Application\SemartHris\Component\Contract\Model\Contractable;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class UpdateJoinDateFromContractSubscriber implements EventSubscriberInterface
{
    /**
     * @param GenericEvent $event
     */
    public function updateFromGenericEvent(GenericEvent $event)
    {
        $entity = $event->getSubject();
        if (!$entity instanceof EmployeeInterface) {
            return;
        }

        $this->updateEmployeeJoinDate($entity);
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function updateFromControllerEvent(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        if (!$entity instanceof EmployeeInterface) {
            return;
        }

        $this->updateEmployeeJoinDate($entity);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EasyAdminEvents::PRE_PERSIST => ['updateFromGenericEvent', 0],
            KernelEvents::VIEW => ['updateFromControllerEvent', EventPriorities::PRE_WRITE],
        ];
    }

    /**
     * @param EmployeeInterface $employee
     */
    private function updateEmployeeJoinDate(EmployeeInterface $employee)
    {
        if ($employee instanceof Contractable && !$employee->getJoinDate()) {
            $employee->setJoinDate($employee->getContract()->getStartDate());
        }
    }
}

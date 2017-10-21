<?php

namespace KejawenLab\Application\SemartHris\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use KejawenLab\Application\SemartHris\Component\Job\Model\MutationInterface;
use KejawenLab\Application\SemartHris\Component\Job\Service\SetEmployeeNewJobService;
use KejawenLab\Application\SemartHris\Component\Job\Service\SetOldJobMutationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class SetOldJobMutationSubscriber implements EventSubscriberInterface
{
    /**
     * @param GenericEvent $event
     */
    public function setJobFromGenericEvent(GenericEvent $event)
    {
        $entity = $event->getSubject();
        if (!$entity instanceof MutationInterface) {
            return;
        }

        SetOldJobMutationService::setOldJob($entity);
        SetEmployeeNewJobService::setNewJob($entity);
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function setJobFromControllerEvent(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        if (!$entity instanceof MutationInterface) {
            return;
        }

        SetOldJobMutationService::setOldJob($entity);
        SetEmployeeNewJobService::setNewJob($entity);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EasyAdminEvents::PRE_PERSIST => ['setJobFromGenericEvent', 0],
            KernelEvents::VIEW => ['setJobFromControllerEvent', EventPriorities::PRE_WRITE],
        ];
    }
}

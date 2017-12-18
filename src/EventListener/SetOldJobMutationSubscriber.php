<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\Application\SemartHris\Component\Job\Model\MutationInterface;
use KejawenLab\Application\SemartHris\Component\Job\Service\SetEmployeeNewJob;
use KejawenLab\Application\SemartHris\Component\Job\Service\SetOldJobMutation;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SetOldJobMutationSubscriber implements EventSubscriber
{
    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof MutationInterface) {
            return;
        }

        SetOldJobMutation::setOldJob($entity);
        SetEmployeeNewJob::setNewJob($entity);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof MutationInterface) {
            return;
        }

        SetOldJobMutation::setOldJob($entity);
        SetEmployeeNewJob::setNewJob($entity);
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::preUpdate];
    }
}

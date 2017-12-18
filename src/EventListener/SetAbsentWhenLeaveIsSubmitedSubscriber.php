<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\Application\SemartHris\Component\Leave\Model\LeaveInterface;
use KejawenLab\Application\SemartHris\Component\Leave\Service\SetAbsentWhenLeaveIsSubmited;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SetAbsentWhenLeaveIsSubmitedSubscriber implements EventSubscriber
{
    /**
     * @var SetAbsentWhenLeaveIsSubmited
     */
    private $service;

    /**
     * @param SetAbsentWhenLeaveIsSubmited $service
     */
    public function __construct(SetAbsentWhenLeaveIsSubmited $service)
    {
        $this->service = $service;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof LeaveInterface) {
            return;
        }

        $this->service->setAbsent($entity);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof LeaveInterface) {
            return;
        }

        $this->service->setAbsent($entity);
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::preUpdate];
    }
}

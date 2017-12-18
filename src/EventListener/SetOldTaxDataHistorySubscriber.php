<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\Application\SemartHris\Component\Tax\Model\TaxGroupHistoryInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Service\SetNewTaxDataHistory;
use KejawenLab\Application\SemartHris\Component\Tax\Service\SetOldTaxDataHistory;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SetOldTaxDataHistorySubscriber implements EventSubscriber
{
    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof TaxGroupHistoryInterface) {
            return;
        }

        SetOldTaxDataHistory::setOldTaxData($entity);
        SetNewTaxDataHistory::setNewTaxData($entity);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof TaxGroupHistoryInterface) {
            return;
        }

        SetOldTaxDataHistory::setOldTaxData($entity);
        SetNewTaxDataHistory::setNewTaxData($entity);
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::preUpdate];
    }
}

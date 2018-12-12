<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\Application\SemartHris\Component\Job\Model\CareerHistoryable;
use KejawenLab\Application\SemartHris\Component\Job\Service\AddCareerHistory;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AddCareerHistorySubscriber implements EventSubscriber
{
    /**
     * @var AddCareerHistory
     */
    private $addCareerHistoryService;

    /**
     * @param AddCareerHistory $service
     */
    public function __construct(AddCareerHistory $service)
    {
        $this->addCareerHistoryService = $service;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof CareerHistoryable) {
            return;
        }

        $this->addCareerHistoryService->store($entity);
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist];
    }
}

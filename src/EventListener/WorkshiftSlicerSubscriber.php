<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\WorkshiftInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\WorkshiftRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\WorkshiftSlicer;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class WorkshiftSlicerSubscriber implements EventSubscriber
{
    /**
     * @var WorkshiftSlicer
     */
    private $workshiftSlicer;

    /**
     * @var WorkshiftRepositoryInterface
     */
    private $workshiftRepository;

    /**
     * @param WorkshiftSlicer              $workshiftSlicer
     * @param WorkshiftRepositoryInterface $repository
     */
    public function __construct(WorkshiftSlicer $workshiftSlicer, WorkshiftRepositoryInterface $repository)
    {
        $this->workshiftSlicer = $workshiftSlicer;
        $this->workshiftRepository = $repository;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof WorkshiftInterface) {
            return;
        }

        $this->slice($entity);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof WorkshiftInterface) {
            return;
        }

        $this->slice($entity);
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::preUpdate];
    }

    /**
     * @param WorkshiftInterface $workshift
     */
    private function slice(WorkshiftInterface $workshift): void
    {
        if ($sliceable = $this->workshiftRepository->findInterSectionWorkshift($workshift)) {
            $this->workshiftSlicer->slice($sliceable, $workshift);
        }
    }
}

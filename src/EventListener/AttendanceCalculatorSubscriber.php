<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\AttendanceCalculator;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceCalculatorSubscriber implements EventSubscriber
{
    /**
     * @var AttendanceCalculator
     */
    private $attandanceCalculatorService;

    /**
     * @param AttendanceCalculator $service
     */
    public function __construct(AttendanceCalculator $service)
    {
        $this->attandanceCalculatorService = $service;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof AttendanceInterface) {
            return;
        }

        $this->attandanceCalculatorService->calculate($entity);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof AttendanceInterface) {
            return;
        }

        $this->attandanceCalculatorService->calculate($entity);
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::preUpdate];
    }
}

<?php

namespace KejawenLab\Application\SemartHris\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\AttendanceCalculator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class AttendanceCalculatorSubscriber implements EventSubscriberInterface
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
     * @param GenericEvent $event
     */
    public function calculateFromGenericEvent(GenericEvent $event)
    {
        $entity = $event->getSubject();
        if (!$entity instanceof AttendanceInterface) {
            return;
        }

        $this->calculate($entity);
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function calculateFromControllerEvent(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        if (!$entity instanceof AttendanceInterface) {
            return;
        }

        $this->calculate($entity);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EasyAdminEvents::PRE_PERSIST => ['calculateFromGenericEvent', 0],
            EasyAdminEvents::PRE_UPDATE => ['calculateFromGenericEvent', 0],
            KernelEvents::VIEW => ['calculateFromControllerEvent', EventPriorities::PRE_WRITE],
        ];
    }

    /**
     * @param AttendanceInterface $attendance
     */
    private function calculate(AttendanceInterface $attendance): void
    {
        $this->attandanceCalculatorService->calculate($attendance);
    }
}

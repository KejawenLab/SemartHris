<?php

namespace KejawenLab\Application\SemartHris\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Service\OvertimeCalculator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class OvertimeCalculatorSubscriber implements EventSubscriberInterface
{
    /**
     * @var OvertimeCalculator
     */
    private $overtimeCalculatorService;

    /**
     * @var array
     */
    private $offDay;

    /**
     * @param OvertimeCalculator $service
     * @param string             $offDayPerWeek
     */
    public function __construct(OvertimeCalculator $service, string $offDayPerWeek)
    {
        $this->overtimeCalculatorService = $service;
        $this->offDay = explode(',', $offDayPerWeek);
    }

    /**
     * @param GenericEvent $event
     */
    public function calculateFromGenericEvent(GenericEvent $event)
    {
        $entity = $event->getSubject();
        if (!$entity instanceof OvertimeInterface) {
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
        if (!$entity instanceof OvertimeInterface) {
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
     * @param OvertimeInterface $overtime
     */
    private function calculate(OvertimeInterface $overtime): void
    {
        if (!$overtime->isHoliday()) {
            if (in_array($overtime->getOvertimeDate()->format('N'), $this->offDay)) {
                $overtime->setHoliday(true);
            }
        }

        $this->overtimeCalculatorService->calculate($overtime);
    }
}

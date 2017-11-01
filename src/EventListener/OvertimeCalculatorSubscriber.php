<?php

namespace KejawenLab\Application\SemartHris\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\Application\SemartHris\Component\Holiday\Repository\HolidayRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Service\OvertimeCalculator;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class OvertimeCalculatorSubscriber implements EventSubscriber
{
    /**
     * @var OvertimeCalculator
     */
    private $overtimeCalculatorService;

    /**
     * @var HolidayRepositoryInterface
     */
    private $holidayRepository;

    /**
     * @param OvertimeCalculator         $service
     * @param HolidayRepositoryInterface $holidayRepository
     */
    public function __construct(OvertimeCalculator $service, HolidayRepositoryInterface $holidayRepository)
    {
        $this->overtimeCalculatorService = $service;
        $this->holidayRepository = $holidayRepository;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof OvertimeInterface) {
            return;
        }

        $this->calculate($entity);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof OvertimeInterface) {
            return;
        }

        $this->calculate($entity);
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::preUpdate];
    }

    /**
     * @param OvertimeInterface $overtime
     */
    private function calculate(OvertimeInterface $overtime): void
    {
        if (!$overtime->isHoliday()) {
            if ($this->holidayRepository->isHoliday($overtime->getOvertimeDate())) {
                $overtime->setHoliday(true);
            }
        }

        $this->overtimeCalculatorService->calculate($overtime);
    }
}

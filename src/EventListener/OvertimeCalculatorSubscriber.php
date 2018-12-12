<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Holiday\Repository\HolidayRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Service\OvertimeCalculator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var bool
     */
    private $autoApproved;

    /**
     * @param OvertimeCalculator         $service
     * @param HolidayRepositoryInterface $holidayRepository
     * @param TokenStorageInterface      $tokenStorage
     * @param bool                       $autoApproved
     */
    public function __construct(
        OvertimeCalculator $service,
        HolidayRepositoryInterface $holidayRepository,
        TokenStorageInterface $tokenStorage,
        bool $autoApproved = false
    ) {
        $this->overtimeCalculatorService = $service;
        $this->holidayRepository = $holidayRepository;
        $this->tokenStorage = $tokenStorage;
        $this->autoApproved = $autoApproved;
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

        if ($this->autoApproved && $token = $this->tokenStorage->getToken()) {
            if ($token->getUser() instanceof EmployeeInterface) {
                $overtime->setApprovedBy($token->getUser());
            }
        }

        $this->overtimeCalculatorService->calculate($overtime);
    }
}

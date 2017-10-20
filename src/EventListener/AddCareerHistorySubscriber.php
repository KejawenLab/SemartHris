<?php

namespace KejawenLab\Application\SemartHris\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use KejawenLab\Application\SemartHris\Component\Job\Model\CareerHistoryable;
use KejawenLab\Application\SemartHris\Component\Job\Service\AddCareerHistoryService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class AddCareerHistorySubscriber implements EventSubscriberInterface
{
    /**
     * @var AddCareerHistoryService
     */
    private $addCareerHistoryService;

    /**
     * @param AddCareerHistoryService $service
     */
    public function __construct(AddCareerHistoryService $service)
    {
        $this->addCareerHistoryService = $service;
    }

    /**
     * @param GenericEvent $event
     */
    public function addHistoryFromGenericEvent(GenericEvent $event)
    {
        $entity = $event->getSubject();
        if (!$entity instanceof CareerHistoryable) {
            return;
        }

        $this->addCareerHistory($entity);
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function addHistoryFromControllerEvent(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        if (!$entity instanceof CareerHistoryable) {
            return;
        }

        $this->addCareerHistory($entity);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EasyAdminEvents::PRE_PERSIST => ['addHistoryFromGenericEvent', 0],
            KernelEvents::VIEW => ['addHistoryFromControllerEvent', EventPriorities::PRE_WRITE],
        ];
    }

    /**
     * @param CareerHistoryable $careerHistoryable
     */
    private function addCareerHistory(CareerHistoryable $careerHistoryable)
    {
        $this->addCareerHistoryService->store($careerHistoryable);
    }
}

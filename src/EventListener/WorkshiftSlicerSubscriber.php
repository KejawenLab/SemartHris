<?php

namespace KejawenLab\Application\SemartHris\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\WorkshiftInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\WorkshiftRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\WorkshiftSlicer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class WorkshiftSlicerSubscriber implements EventSubscriberInterface
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
     * @param GenericEvent $event
     */
    public function sliceFromGenericEvent(GenericEvent $event)
    {
        $entity = $event->getSubject();
        if (!$entity instanceof WorkshiftInterface) {
            return;
        }

        $this->slice($entity);
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function sliceFromControllerEvent(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        if (!$entity instanceof WorkshiftInterface) {
            return;
        }

        $this->slice($entity);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EasyAdminEvents::PRE_PERSIST => ['sliceFromGenericEvent', 0],
            EasyAdminEvents::PRE_UPDATE => ['sliceFromGenericEvent', 0],
            KernelEvents::VIEW => ['sliceFromControllerEvent', EventPriorities::PRE_WRITE],
        ];
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

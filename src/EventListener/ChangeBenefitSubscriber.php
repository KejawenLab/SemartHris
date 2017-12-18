<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\Application\SemartHris\Component\Salary\Model\BenefitHistoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Service\ChangeBenefit;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ChangeBenefitSubscriber implements EventSubscriber
{
    /**
     * @var ChangeBenefit
     */
    private $changeBenefitService;

    /**
     * @param ChangeBenefit $changeBenefitService
     */
    public function __construct(ChangeBenefit $changeBenefitService)
    {
        $this->changeBenefitService = $changeBenefitService;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof BenefitHistoryInterface) {
            $this->changeBenefitService->apply($entity);
        }
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof BenefitHistoryInterface) {
            $this->changeBenefitService->apply($entity);
        }
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::preUpdate];
    }
}

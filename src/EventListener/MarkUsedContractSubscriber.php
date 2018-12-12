<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\Application\SemartHris\Component\Contract\Model\Contractable;
use KejawenLab\Application\SemartHris\Component\Contract\Service\CheckContract;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MarkUsedContractSubscriber implements EventSubscriber
{
    /**
     * @var CheckContract
     */
    private $checkContractService;

    /**
     * @param CheckContract $service
     */
    public function __construct(CheckContract $service)
    {
        $this->checkContractService = $service;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Contractable && $contract = $entity->getContract()) {
            $this->checkContractService->markUsedContract($contract);
        }
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist];
    }
}

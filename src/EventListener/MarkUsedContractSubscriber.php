<?php

namespace KejawenLab\Application\SemartHris\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use KejawenLab\Application\SemartHris\Component\Contract\Model\Contractable;
use KejawenLab\Application\SemartHris\Component\Contract\Service\CheckContractService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class MarkUsedContractSubscriber implements EventSubscriberInterface
{
    /**
     * @var CheckContractService
     */
    private $checkContractService;

    /**
     * @param CheckContractService $service
     */
    public function __construct(CheckContractService $service)
    {
        $this->checkContractService = $service;
    }

    /**
     * @param GenericEvent $event
     */
    public function markUsedContractFromGenericEvent(GenericEvent $event)
    {
        $entity = $event->getSubject();
        if ($entity instanceof Contractable && $contract = $entity->getContract()) {
            $this->checkContractService->markUsedContract($contract);
        }
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function markUsedContractFromControllerEvent(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        if ($entity instanceof Contractable && $contract = $entity->getContract()) {
            $this->checkContractService->markUsedContract($contract);
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EasyAdminEvents::PRE_PERSIST => ['markUsedContractFromGenericEvent', 0],
            KernelEvents::VIEW => ['markUsedContractFromControllerEvent', EventPriorities::PRE_WRITE],
        ];
    }
}

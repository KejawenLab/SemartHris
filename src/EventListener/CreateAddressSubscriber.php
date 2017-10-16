<?php

namespace KejawenLab\Application\SemartHris\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use KejawenLab\Application\SemartHris\Component\Address\Model\Addressable;
use KejawenLab\Application\SemartHris\Component\Address\Service\CreateAddressFromAddressable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class CreateAddressSubscriber implements EventSubscriberInterface
{
    /**
     * @var CreateAddressFromAddressable
     */
    private $addressService;

    /**
     * @param CreateAddressFromAddressable $addressService
     */
    public function __construct(CreateAddressFromAddressable $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * @param GenericEvent $event
     */
    public function create(GenericEvent $event)
    {
        $subject = $event->getSubject();
        if ($subject instanceof Addressable) {
            $this->addressService->createAddress($subject);
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EasyAdminEvents::PRE_PERSIST => ['create', 0],
            KernelEvents::VIEW => ['create', EventPriorities::POST_WRITE],
        ];
    }
}

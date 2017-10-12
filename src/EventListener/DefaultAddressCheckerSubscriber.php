<?php

namespace KejawenLab\Application\SemarHris\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\Application\SemarHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemarHris\Component\Address\Service\DefaultAddressChecker;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class DefaultAddressCheckerSubscriber implements EventSubscriber
{
    /**
     * @var DefaultAddressChecker
     */
    private $addressChecker;

    /**
     * @param DefaultAddressChecker $addressChecker
     */
    public function __construct(DefaultAddressChecker $addressChecker)
    {
        $this->addressChecker = $addressChecker;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getEntity();
        if ($entity instanceof AddressInterface) {
            $this->addressChecker->unsetDefaultExcept($entity);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getEntity();
        if ($entity instanceof AddressInterface) {
            $this->addressChecker->unsetDefaultExcept($entity);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getEntity();
        if ($entity instanceof AddressInterface && $entity->isDefaultAddress()) {
            $this->addressChecker->setRandomDefault($entity);
        }
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [Events::prePersist, Events::preUpdate, Events::preRemove];
    }
}

<?php

namespace KejawenLab\Application\SemarHris\EventListener;

use KejawenLab\Application\SemarHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemarHris\Component\Address\Service\DefaultAddressChecker;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class DefaultAddressCheckerSubscriber
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
     * @param GenericEvent $genericEvent
     */
    public function checkDefaultAddress(GenericEvent $genericEvent): void
    {
        if ($genericEvent->getSubject() instanceof AddressInterface) {
            $this->addressChecker->unsetDefaultExcept($genericEvent->getSubject());
        }
    }
}

<?php

namespace KejawenLab\Application\SemarHris\Component\Address\Service;

use KejawenLab\Application\SemarHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemarHris\Component\Address\Repository\AddressRepositoryFactory;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
final class DefaultAddressChecker
{
    /**
     * @var AddressRepositoryFactory
     */
    private $addressRepositoryFactory;

    /**
     * @param AddressRepositoryFactory $addressRepositoryFactory
     */
    public function __construct(AddressRepositoryFactory $addressRepositoryFactory)
    {
        $this->addressRepositoryFactory = $addressRepositoryFactory;
    }

    /**
     * @param AddressInterface $address
     */
    public function unsetDefaultExcept(AddressInterface $address): void
    {
        $this->addressRepositoryFactory->getRepositoryForClass($address)->unsetDefaultExcept($address);
    }
}

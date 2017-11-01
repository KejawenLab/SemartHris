<?php

namespace KejawenLab\Application\SemartHris\Component\Address\Service;

use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemartHris\Component\Address\Repository\AddressRepositoryFactory;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class DefaultAddressChecker
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
        if (!$address->isDefaultAddress()) {
            return;
        }

        $repository = $this->addressRepositoryFactory->getRepositoryFor(get_class($address));
        $repository->unsetDefaultExcept($address);

        $addressable = $address->getAddressable();
        $addressable->setAddress($address);

        $repository->apply($addressable);
    }

    /**
     * @param AddressInterface $address
     */
    public function setRandomDefault(AddressInterface $address): void
    {
        $repository = $this->addressRepositoryFactory->getRepositoryFor(get_class($address));
        $newDefault = $repository->setRandomDefault();

        $addressable = $address->getAddressable();
        $addressable->setAddress($newDefault);

        $repository->apply($addressable);
    }
}

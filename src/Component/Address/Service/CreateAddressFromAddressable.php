<?php

namespace KejawenLab\Application\SemartHris\Component\Address\Service;

use KejawenLab\Application\SemartHris\Component\Address\Model\Addressable;
use KejawenLab\Application\SemartHris\Component\Address\Repository\AddressRepositoryFactory;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class CreateAddressFromAddressable
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
     * @param Addressable $addressable
     */
    public function createAddress(Addressable $addressable)
    {
        $this->addressRepositoryFactory->getRepositoryFor($addressable->getAddressClass())->saveAddress($addressable);
    }
}

<?php

namespace Persona\Hris\Component\Address\Repository;

use Persona\Hris\Component\Address\Model\AddressInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
final class AddressRepositoryFactory
{
    /**
     * @var AddressRepositoryInterface[]
     */
    private $repositories;

    /**
     * @param AddressRepositoryInterface[] $addressRepositories
     */
    public function __construct(array $addressRepositories = [])
    {
        foreach ($addressRepositories as $addressRepository) {
            $this->addRepository($addressRepository);
        }
    }

    /**
     * @param AddressInterface $address
     *
     * @return AddressRepositoryInterface
     */
    public function getRepositoryForClass(AddressInterface $address): AddressRepositoryInterface
    {
        //TODO
    }

    /**
     * @param AddressRepositoryInterface $addressRepository
     */
    private function addRepository(AddressRepositoryInterface $addressRepository): void
    {
        $this->repositories[$addressRepository->getEntityClass()] = $addressRepository;
    }
}

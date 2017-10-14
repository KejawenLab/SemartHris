<?php

namespace KejawenLab\Application\SemartHris\Component\Address\Repository;

use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class AddressRepositoryFactory
{
    const ADDRESS_REPOSITORY_SERVICE_TAG = 'semarthris.address_repository';

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
    public function getRepositoryFor(AddressInterface $address): AddressRepositoryInterface
    {
        $entityClass = get_class($address);
        if (!$repository = $this->repositories[$entityClass]) {
            throw new \InvalidArgumentException(sprintf('Repository for %s not found.', $entityClass));
        }

        return $repository;
    }

    /**
     * @param AddressRepositoryInterface $addressRepository
     */
    private function addRepository(AddressRepositoryInterface $addressRepository): void
    {
        $this->repositories[$addressRepository->getEntityClass()] = $addressRepository;
    }
}

<?php

namespace KejawenLab\Application\SemartHris\Component\Address\Repository;

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
     * @param string $addressClasss
     *
     * @return AddressRepositoryInterface
     */
    public function getRepositoryFor(string $addressClasss): AddressRepositoryInterface
    {
        if (!$repository = $this->repositories[$addressClasss]) {
            throw new \InvalidArgumentException(sprintf('Repository for class "%s" not found.', $addressClasss));
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

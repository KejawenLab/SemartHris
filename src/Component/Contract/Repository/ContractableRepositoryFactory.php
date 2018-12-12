<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Contract\Repository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ContractableRepositoryFactory
{
    const SEMARTHRIS_CONTRACTABLE_REPOSITORY = 'semarthris.contract_repository';

    /**
     * @var array
     */
    private $repositories;

    /**
     * @param ContractableRepositoryInterface[] $repositories
     */
    public function __construct(array $repositories = [])
    {
        $this->repositories = [];
        foreach ($repositories as $repository) {
            $this->addRepository($repository);
        }
    }

    /**
     * @return ContractableRepositoryInterface[]
     */
    public function getRepositories(): array
    {
        return $this->repositories;
    }

    /**
     * @param ContractableRepositoryInterface $repository
     */
    public function addRepository(ContractableRepositoryInterface $repository): void
    {
        $this->repositories[get_class($repository)] = $repository;
    }
}

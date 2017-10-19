<?php

namespace KejawenLab\Application\SemartHris\Component\Contract\Service;

use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;
use KejawenLab\Application\SemartHris\Component\Contract\Repository\ContractableRepositoryFactory;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class CheckContractService
{
    /**
     * @var ContractableRepositoryFactory
     */
    private $contractableRepositoryFactory;

    /**
     * @param ContractableRepositoryFactory $repositoryFactory
     */
    public function __construct(ContractableRepositoryFactory $repositoryFactory)
    {
        $this->contractableRepositoryFactory = $repositoryFactory;
    }

    /**
     * @param ContractInterface $contract
     *
     * @return bool
     */
    public function isAlreadyUsedContract(ContractInterface $contract): bool
    {
        $repositories = $this->contractableRepositoryFactory->getRepositories();
        foreach ($repositories as $repository) {
            if ($repository->findByContract($contract)) {
                return true;
            }
        }

        return false;
    }
}

<?php

namespace KejawenLab\Application\SemartHris\Component\Contract\Service;

use KejawenLab\Application\SemartHris\Component\Contract\Model\Contractable;
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
     * @param Contractable $contractable
     *
     * @return bool
     */
    public function isAlreadyUsedContract(Contractable $contractable): bool
    {
        $count = 0;
        $repositories = $this->contractableRepositoryFactory->getRepositories();
        foreach ($repositories as $repository) {
            if ($exists = $repository->findByContract($contractable->getContract())) {
                foreach ($exists as $exist) {
                    if ($exist->getContract()->getId() !== $contractable->getContract()->getId()) {
                        ++$count;
                    }
                }
            }
        }

        if (1 <= $count) {
            return true;
        }

        return false;
    }
}

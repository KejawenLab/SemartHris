<?php

namespace KejawenLab\Application\SemartHris\Component\Contract\Service;

use KejawenLab\Application\SemartHris\Component\Contract\Model\Contractable;
use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;
use KejawenLab\Application\SemartHris\Component\Contract\Repository\ContractableRepositoryFactory;
use KejawenLab\Application\SemartHris\Component\Contract\Repository\ContractRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class CheckContract
{
    /**
     * @var ContractableRepositoryFactory
     */
    private $contractableRepositoryFactory;

    /**
     * @var ContractRepositoryInterface
     */
    private $contractRepository;

    /**
     * @param ContractableRepositoryFactory $repositoryFactory
     * @param ContractRepositoryInterface   $contractRepository
     */
    public function __construct(ContractableRepositoryFactory $repositoryFactory, ContractRepositoryInterface $contractRepository)
    {
        $this->contractableRepositoryFactory = $repositoryFactory;
        $this->contractRepository = $contractRepository;
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
                    if ($contractable->getId()) {
                        if ($exist->getContract()->getId() !== $contractable->getContract()->getId()) {
                            ++$count;
                        }
                    } else {
                        if ($exist->getContract()->getId() === $contractable->getContract()->getId()) {
                            ++$count;
                        }
                    }
                }
            }
        }

        if (0 < $count) {
            return true;
        }

        return false;
    }

    /**
     * @param ContractInterface $contract
     */
    public function markUsedContract(ContractInterface $contract)
    {
        $contract->setUsed(true);
        $this->contractRepository->update($contract);
    }
}

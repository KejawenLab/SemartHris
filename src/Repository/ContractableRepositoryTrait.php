<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Contract\Model\Contractable;
use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
trait ContractableRepositoryTrait
{
    /**
     * @param ContractInterface $contract
     *
     * @return Contractable[]
     */
    public function findByContract(ContractInterface $contract): array
    {
        return $this->entityManager->getRepository($this->entityClass)->findBy([
            'contract' => $contract,
            'deletedAt' => null,
        ]);
    }
}

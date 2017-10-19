<?php

namespace KejawenLab\Application\SemartHris\Component\Contract\Repository;

use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface ContractableRepositoryInterface
{
    /**
     * @param ContractInterface $contract
     *
     * @return ContractInterface|null
     */
    public function findByContract(ContractInterface $contract): ? ContractInterface;
}

<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Contract\Repository;

use KejawenLab\Application\SemartHris\Component\Contract\Model\Contractable;
use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface ContractableRepositoryInterface
{
    /**
     * @param ContractInterface $contract
     *
     * @return Contractable[]
     */
    public function findByContract(ContractInterface $contract): array;
}

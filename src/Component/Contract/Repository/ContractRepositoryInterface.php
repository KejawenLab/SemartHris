<?php

namespace KejawenLab\Application\SemartHris\Component\Contract\Repository;

use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface ContractRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return ContractInterface|null
     */
    public function find(string $id): ? ContractInterface;

    /**
     * @return array
     */
    public function findAllTags(): array;

    /**
     * @param string $type
     *
     * @return array
     */
    public function findByType(string $type): array;

    /**
     * @param ContractInterface $contract
     */
    public function update(ContractInterface $contract): void;
}

<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Contract\Repository;

use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface ContractRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return ContractInterface|null
     */
    public function find(?string $id): ? ContractInterface;

    /**
     * @param Request $request
     *
     * @return array
     */
    public function search(Request $request): array;

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

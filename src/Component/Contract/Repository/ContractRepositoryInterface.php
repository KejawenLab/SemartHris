<?php

namespace KejawenLab\Application\SemartHris\Component\Contract\Repository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface ContractRepositoryInterface
{
    /**
     * @return array
     */
    public function findAllTags(): array;
}

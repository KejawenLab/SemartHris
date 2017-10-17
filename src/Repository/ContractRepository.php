<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Contract\Repository\ContractRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class ContractRepository extends Repository implements ContractRepositoryInterface
{
    /**
     * @return array
     */
    public function findAllTags(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('c.tags');
        $queryBuilder->from($this->entityClass, 'c');

        return $queryBuilder->getQuery()->getResult();
    }
}

<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Reason\Model\ReasonInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Model\ReasonRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class ReasonRepository extends Repository implements ReasonRepositoryInterface
{
    /**
     * @param string $type
     *
     * @return ReasonInterface[]
     */
    public function findByType(string $type): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('r.id, r.code, r.name');
        $queryBuilder->from($this->entityClass, 'r');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('r.type', $queryBuilder->expr()->literal($type)));

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $id
     *
     * @return ReasonInterface|null
     */
    public function find(string $id): ? ReasonInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->find($id);
    }
}

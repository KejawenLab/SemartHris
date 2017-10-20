<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;
use KejawenLab\Application\SemartHris\Component\Contract\Repository\ContractRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class ContractRepository extends Repository implements ContractRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return ContractInterface|null
     */
    public function find(string $id): ? ContractInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->find($id);
    }

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

    /**
     * @param string $type
     *
     * @return array
     */
    public function findByType(string $type): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('c.id, c.letterNumber, c.subject');
        $queryBuilder->from($this->entityClass, 'c');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('c.type', ':type'));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('c.used', $queryBuilder->expr()->literal(false)));
        $queryBuilder->setParameter('type', $type);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param ContractInterface $contract
     */
    public function update(ContractInterface $contract): void
    {
        $this->entityManager->persist($contract);
        $this->entityManager->flush();
    }
}

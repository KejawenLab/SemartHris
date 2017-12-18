<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;
use KejawenLab\Application\SemartHris\Component\Contract\Repository\ContractRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ContractRepository extends Repository implements ContractRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return ContractInterface|null
     */
    public function find(?string $id): ? ContractInterface
    {
        return $this->doFind($id);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function search(Request $request): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('c.tags');
        $queryBuilder->from($this->entityClass, 'c');
        $queryBuilder->orWhere($queryBuilder->expr()->like('c.tags', ':search'));
        $queryBuilder->setParameter('search', sprintf('%%%s%%', $request->query->get('search')));

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function findByType(string $type): array
    {
        if (!$type) {
            return [];
        }

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

<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Screening\ScreeningStatus;
use KejawenLab\Application\SemartHris\Entity\ScreeningCandidate;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ScreeningCandidateRepository extends Repository
{
    /**
     * @param string $term name or phone fragment
     *
     * @return array
     */
    public function search(string $term): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'c');
        $queryBuilder->addSelect('c.id');
        $queryBuilder->addSelect('c.name');
        $queryBuilder->addSelect('c.phone');
        $queryBuilder->addSelect('c.position');
        $queryBuilder->addSelect('c.status');
        $queryBuilder->where($queryBuilder->expr()->like('c.name', ':term'));
        $queryBuilder->orWhere($queryBuilder->expr()->like('c.phone', ':term'));
        $queryBuilder->setParameter('term', sprintf('%%%s%%', $term));
        $queryBuilder->setMaxResults(20);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param array $ids
     *
     * @return ScreeningCandidate[]
     */
    public function findByIds(array $ids): array
    {
        if (0 === count($ids)) {
            return [];
        }

        return $this->entityManager->getRepository($this->entityClass)->findBy(['id' => $ids]);
    }

    /**
     * @return ScreeningCandidate[]
     */
    public function findReady(): array
    {
        return $this->entityManager->getRepository($this->entityClass)->findBy(
            ['status' => [ScreeningStatus::READY, ScreeningStatus::QUEUED]],
            ['createdAt' => 'ASC']
        );
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ScreeningRepository extends Repository
{
    /**
     * @param string $outcome
     *
     * @return array
     */
    public function findByOutcome(string $outcome): array
    {
        return $this->entityManager->getRepository($this->entityClass)->findBy(
            ['outcome' => $outcome],
            ['completedAt' => 'DESC']
        );
    }

    /**
     * Outcome counts for the dashboard. Only real stored rows,
     * never fabricated analytics.
     *
     * @return array outcome => count
     */
    public function countByOutcome(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 's');
        $queryBuilder->addSelect('s.outcome');
        $queryBuilder->addSelect('COUNT(s.id) AS total');
        $queryBuilder->groupBy('s.outcome');

        $counts = [];
        foreach ($queryBuilder->getQuery()->getResult() as $row) {
            $counts[$row['outcome']] = (int) $row['total'];
        }

        return $counts;
    }
}

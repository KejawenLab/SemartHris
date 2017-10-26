<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Holiday\Repository\HolidayRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class HolidayRepository extends Repository implements HolidayRepositoryInterface
{
    /**
     * @param \DateTimeInterface $date
     *
     * @return bool
     */
    public function isHoliday(\DateTimeInterface $date): bool
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('COUNT(1)');
        $queryBuilder->from($this->entityClass, 'h');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('h.holidayDate', $queryBuilder->expr()->literal($date->format('Y-m-d'))));

        if ($queryBuilder->getQuery()->getSingleScalarResult()) {
            return true;
        }

        return false;
    }
}

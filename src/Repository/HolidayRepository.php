<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Holiday\Repository\HolidayRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class HolidayRepository extends Repository implements HolidayRepositoryInterface
{
    /**
     * @var array
     */
    private $offDayPerWeek;

    /**.
     * @param string $offDayPerWeek
     */
    public function __construct(string $offDayPerWeek)
    {
        $offDays = explode(',', $offDayPerWeek);
        foreach ($offDays as $offDay) {
            $this->offDayPerWeek[] = $offDay;
        }
    }

    /**
     * @param \DateTimeInterface $date
     *
     * @return bool
     */
    public function isHoliday(\DateTimeInterface $date): bool
    {
        if (in_array($date->format('N'), $this->offDayPerWeek)) {
            return true;
        }

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

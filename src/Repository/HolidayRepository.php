<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Holiday\Model\HolidayInterface;
use KejawenLab\Application\SemartHris\Component\Holiday\Repository\HolidayRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
        if ($this->isWeekendHoliday($date)) {
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

    /**
     * @param \DateTimeInterface $date
     *
     * @return bool
     */
    public function isWeekendHoliday(\DateTimeInterface $date): bool
    {
        if (in_array($date->format('N'), $this->offDayPerWeek)) {
            return true;
        }

        return false;
    }

    /**
     * @param \DateTimeInterface $date
     *
     * @return HolidayInterface|null
     */
    public function getHoliday(\DateTimeInterface $date): ? HolidayInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy(['holidayDate' => $date]);
    }
}

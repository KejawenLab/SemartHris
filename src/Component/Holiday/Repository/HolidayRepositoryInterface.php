<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Holiday\Repository;

use KejawenLab\Application\SemartHris\Component\Holiday\Model\HolidayInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface HolidayRepositoryInterface
{
    /**
     * @param \DateTimeInterface $date
     *
     * @return bool
     */
    public function isHoliday(\DateTimeInterface $date): bool;

    /**
     * @param \DateTimeInterface $date
     *
     * @return bool
     */
    public function isWeekendHoliday(\DateTimeInterface $date): bool;

    /**
     * @param \DateTimeInterface $date
     *
     * @return HolidayInterface|null
     */
    public function getHoliday(\DateTimeInterface $date): ? HolidayInterface;
}

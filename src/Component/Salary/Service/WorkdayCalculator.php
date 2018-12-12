<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Service;

use KejawenLab\Application\SemartHris\Component\Holiday\Repository\HolidayRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class WorkdayCalculator
{
    /**
     * @var HolidayRepositoryInterface
     */
    private $holidayRepository;

    /**
     * @param HolidayRepositoryInterface $holidayRepository
     */
    public function __construct(HolidayRepositoryInterface $holidayRepository)
    {
        $this->holidayRepository = $holidayRepository;
    }

    /**
     * @param \DateTimeInterface $month
     * @param int                $dateLimit
     * @param int                $dateStart
     *
     * @return int
     */
    public function getWorkdays(\DateTimeInterface $month, $dateLimit = 0, $dateStart = 1): int
    {
        $workdays = 0;
        $totalDate = $dateLimit ?: $month->format('t');
        for ($i = $dateStart; $i <= $totalDate; ++$i) {
            $date = \DateTime::createFromFormat('Y-m-j', sprintf('%s-%d', $month->format('Y-m'), $i));
            if ($this->holidayRepository->isHoliday($date)) {
                continue;
            }

            ++$workdays;
        }

        return $workdays;
    }
}

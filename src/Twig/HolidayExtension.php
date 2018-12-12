<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Twig;

use KejawenLab\Application\SemartHris\Component\Holiday\Repository\HolidayRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class HolidayExtension extends \Twig_Extension
{
    /**
     * @var HolidayRepositoryInterface
     */
    private $holidayRepository;

    /**
     * @param HolidayRepositoryInterface $repository
     */
    public function __construct(HolidayRepositoryInterface $repository)
    {
        $this->holidayRepository = $repository;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('semarthris_is_holiday', [$this, 'isHoliday']),
            new \Twig_SimpleFunction('semarthris_holiday', [$this, 'getHoliday']),
            new \Twig_SimpleFunction('semarthris_is_weekend_holiday', [$this, 'isWeekendHoliday']),
        ];
    }

    /**
     * @param \DateTimeInterface $date
     *
     * @return bool
     */
    public function isHoliday(\DateTimeInterface $date): bool
    {
        $holiday = $this->holidayRepository->isWeekendHoliday($date);
        if (!$holiday) {
            $holiday = $this->holidayRepository->isHoliday($date);
        }

        return $holiday;
    }

    /**
     * @param \DateTimeInterface $date
     *
     * @return string|null
     */
    public function getHoliday(\DateTimeInterface $date): ? string
    {
        $holiday = $this->holidayRepository->getHoliday($date);
        if ($holiday) {
            return $holiday->getName();
        }

        return $holiday;
    }

    /**
     * @param \DateTimeInterface $date
     *
     * @return bool
     */
    public function isWeekendHoliday(\DateTimeInterface $date): bool
    {
        return $this->holidayRepository->isWeekendHoliday($date);
    }
}

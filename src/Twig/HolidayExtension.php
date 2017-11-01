<?php

namespace KejawenLab\Application\SemartHris\Twig;

use KejawenLab\Application\SemartHris\Component\Holiday\Model\HolidayInterface;
use KejawenLab\Application\SemartHris\Component\Holiday\Repository\HolidayRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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
        return array(
            new \Twig_SimpleFunction('semarthris_is_holiday', array($this, 'isHoliday')),
            new \Twig_SimpleFunction('semarthris_holiday', array($this, 'getHoliday')),
            new \Twig_SimpleFunction('semarthris_is_weekend_holiday', array($this, 'isWeekendHoliday')),
        );
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
     * @return HolidayInterface|null
     */
    public function getHoliday(\DateTimeInterface $date): ? HolidayInterface
    {
        return $this->holidayRepository->getHoliday($date);
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

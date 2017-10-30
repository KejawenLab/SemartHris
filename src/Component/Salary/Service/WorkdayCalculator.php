<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Service;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class WorkdayCalculator
{
    /**
     * @var array
     */
    private $offdayPerWeek;

    /**
     * @param string $offdayPerWeek
     */
    public function __construct(string $offdayPerWeek)
    {
        $this->offdayPerWeek = explode(', ', $offdayPerWeek);
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
            if (in_array($date->format('N'), $this->offdayPerWeek)) {
                continue;
            }

            ++$workdays;
        }

        return $workdays;
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Overtime\Calculator;

use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class Calculator implements OvertimeCalculatorInterface
{
    /**
     * @var int
     */
    protected $workday;

    /**
     * @var Setting
     */
    protected $setting;

    /**
     * @param int $workday
     */
    public function setWorkdayPerWeek(int $workday): void
    {
        $this->workday = $workday;
    }

    /**
     * @param Setting $setting
     */
    public function setSetingg(Setting $setting): void
    {
        $this->setting = $setting;
    }

    /**
     * @param OvertimeInterface $overtime
     *
     * @return float
     */
    protected function getOvertimeHours(OvertimeInterface $overtime): float
    {
        /** @var \DateTime $endHour */
        $endHour = \DateTime::createFromFormat(
            $this->setting->get(SettingKey::DATE_TIME_FORMAT),
            sprintf('%s %s',
                date($this->setting->get(SettingKey::DATE_FORMAT)),
                $overtime->getEndHour()->format($this->setting->get(SettingKey::TIME_FORMAT))
            )
        );

        $startHour = \DateTime::createFromFormat(
            $this->setting->get(SettingKey::DATE_TIME_FORMAT),
            sprintf('%s %s',
                date($this->setting->get(SettingKey::DATE_FORMAT)),
                $overtime->getStartHour()->format($this->setting->get(SettingKey::TIME_FORMAT))
            )
        );

        if ($endHour < $startHour) {
            $endHour->add(new \DateInterval('P1D'));
            $overtime->setOverday(true);
        } else {
            $overtime->setOverday(false);
        }

        $delta = $endHour->diff($startHour, true);
        $hours = $delta->h;
        $minutes = $delta->i;
        if (15 < $minutes) {//Minute adjustment
            if (15 < $minutes && 45 >= $minutes) {
                $hours += 0.5;
            } else {
                ++$hours;
            }
        }

        return $this->breakSub((float) $hours);
    }

    /**
     * @param float $hours
     *
     * @return float
     */
    private function breakSub(float $hours): float
    {
        $realHours = $hours;
        $flag = true;
        while ($flag) {
            if (4 <= $hours) {
                $realHours -= 0.5;
                $hours -= 4;

                $this->breakSub($hours);
            } else {
                $flag = false;
            }
        }

        return $realHours;
    }
}

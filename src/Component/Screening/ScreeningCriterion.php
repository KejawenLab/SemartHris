<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Screening;

/**
 * The five frontline screening dimensions.
 * Ported from HireCall src/types/index.ts ScreeningQuestion key.
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ScreeningCriterion
{
    const NAME_ROLE = 'name_role';
    const EXPERIENCE = 'experience';
    const LOCATION = 'location';
    const SHIFT = 'shift';
    const SALARY = 'salary';

    /**
     * @return array
     */
    public static function getKeys(): array
    {
        return [
            self::NAME_ROLE,
            self::EXPERIENCE,
            self::LOCATION,
            self::SHIFT,
            self::SALARY,
        ];
    }

    /**
     * Criteria that decide the outcome. Name and role confirmation
     * is recorded as evidence but never blocks a candidate.
     *
     * @return array
     */
    public static function getScoredKeys(): array
    {
        return [
            self::EXPERIENCE,
            self::LOCATION,
            self::SHIFT,
            self::SALARY,
        ];
    }
}

<?php

namespace KejawenLab\Application\SemartHris\Component\Employee\Service;

use KejawenLab\Application\SemartHris\Component\Employee\Gender;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class ValidateGender
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public static function isValidType(string $type): bool
    {
        $type = StringUtil::lowercase($type);
        if (!in_array($type, [
            Gender::MALE,
            Gender::FEMALE,
        ])) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public static function getGenders()
    {
        return [
            Gender::MALE,
            Gender::FEMALE,
        ];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public static function convertToText(string $type): string
    {
        $types = [
            Gender::MALE => Gender::MALE_TEXT,
            Gender::FEMALE => Gender::FEMALE_TEXT,
        ];

        if (self::isValidType($type)) {
            return $types[$type];
        }

        return '';
    }
}

<?php

namespace KejawenLab\Application\SemarHris\Component\Employee\Service;

use KejawenLab\Application\SemarHris\Component\Employee\MaritalStatus;
use KejawenLab\Application\SemarHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class ValidateMaritalStatus
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
            MaritalStatus::DISVORCE,
            MaritalStatus::MARRIED,
            MaritalStatus::SINGLE,
        ])) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public static function getReasonTypes()
    {
        return [
            MaritalStatus::DISVORCE,
            MaritalStatus::MARRIED,
            MaritalStatus::SINGLE,
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
            MaritalStatus::SINGLE => MaritalStatus::SINGLE_TEXT,
            MaritalStatus::MARRIED => MaritalStatus::MARRIED_TEXT,
            MaritalStatus::DISVORCE => MaritalStatus::DISVORCE_TEXT,
        ];

        if (self::isValidType($type)) {
            return $types[$type];
        }

        return '';
    }
}

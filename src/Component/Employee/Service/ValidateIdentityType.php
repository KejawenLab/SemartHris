<?php

namespace KejawenLab\Application\SemartHris\Component\Employee\Service;

use KejawenLab\Application\SemartHris\Component\Employee\IdentityType;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class ValidateIdentityType
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
            IdentityType::DRIVER_LISENCE,
            IdentityType::ID_CARD,
            IdentityType::PASSPORT,
        ])) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public static function getIdentityTypes()
    {
        return [
            IdentityType::DRIVER_LISENCE,
            IdentityType::ID_CARD,
            IdentityType::PASSPORT,
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
            IdentityType::DRIVER_LISENCE => IdentityType::DRIVER_LISENCE_TEXT,
            IdentityType::PASSPORT => IdentityType::PASSPORT_TEXT,
            IdentityType::ID_CARD => IdentityType::ID_CARD_TEXT,
        ];

        if (self::isValidType($type)) {
            return $types[$type];
        }

        return '';
    }
}

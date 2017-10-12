<?php

namespace KejawenLab\Application\SemarHris\Component\Employee\Service;

use KejawenLab\Application\SemarHris\Component\Employee\IdentityType;
use KejawenLab\Application\SemarHris\Util\StringUtil;

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
    public static function getReasonTypes()
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

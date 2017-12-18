<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Employee\Service;

use KejawenLab\Application\SemartHris\Component\Employee\IdentityType;
use KejawenLab\Application\SemartHris\Component\ValidateTypeInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidateIdentityType implements ValidateTypeInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public static function isValidType(string $type): bool
    {
        $type = StringUtil::lowercase($type);
        if (!in_array($type, self::getTypes())) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public static function getTypes(): array
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

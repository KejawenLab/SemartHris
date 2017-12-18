<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Employee\Service;

use KejawenLab\Application\SemartHris\Component\Employee\FamilyRelation;
use KejawenLab\Application\SemartHris\Component\ValidateTypeInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidateFamilyRelation implements ValidateTypeInterface
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
            FamilyRelation::COUPLE,
            FamilyRelation::PARENT,
            FamilyRelation::SON,
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
            FamilyRelation::COUPLE => FamilyRelation::COUPLE_TEXT,
            FamilyRelation::PARENT => FamilyRelation::PARENT_TEXT,
            FamilyRelation::SON => FamilyRelation::SON_TEXT,
        ];

        if (self::isValidType($type)) {
            return $types[$type];
        }

        return '';
    }
}

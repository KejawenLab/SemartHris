<?php

namespace KejawenLab\Application\SemarHris\Component\Employee\Service;

use KejawenLab\Application\SemarHris\Component\Employee\FamilyRelation;
use KejawenLab\Application\SemarHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class ValidateFamilyRelation
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
            FamilyRelation::COUPLE,
            FamilyRelation::PARENT,
            FamilyRelation::SON,
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

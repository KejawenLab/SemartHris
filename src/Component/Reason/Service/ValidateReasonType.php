<?php

namespace KejawenLab\Application\SemarHris\Component\Reason\Service;

use KejawenLab\Application\SemarHris\Component\Reason\ReasonType;
use KejawenLab\Application\SemarHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
class ValidateReasonType
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public static function isValidType(string $type): bool
    {
        $type = StringUtil::lowercase($type);
        if (!in_array($type, [ReasonType::ABSENT_CODE, ReasonType::LEAVE_CODE])) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public static function getReasonTypes()
    {
        return [ReasonType::ABSENT_CODE, ReasonType::LEAVE_CODE];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public static function convertToText(string $type): string
    {
        $types = [ReasonType::LEAVE_CODE => ReasonType::LEAVE_TEXT, ReasonType::ABSENT_CODE => ReasonType::ABSENT_TEXT];

        if (array_key_exists($type, $types)) {
            return $types[$type];
        }

        return '';
    }
}

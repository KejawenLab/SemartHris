<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Reason\Service;

use KejawenLab\Application\SemartHris\Component\Reason\ReasonType;
use KejawenLab\Application\SemartHris\Component\ValidateTypeInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidateReasonType implements ValidateTypeInterface
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

        if (self::isValidType($type)) {
            return $types[$type];
        }

        return '';
    }
}

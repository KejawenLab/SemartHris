<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Screening\Service;

use KejawenLab\Application\SemartHris\Component\Screening\ScreeningStatus;
use KejawenLab\Application\SemartHris\Component\ValidateTypeInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidateScreeningStatus implements ValidateTypeInterface
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
            ScreeningStatus::READY,
            ScreeningStatus::QUEUED,
            ScreeningStatus::CALLING,
            ScreeningStatus::COMPLETED,
            ScreeningStatus::NOT_ANSWERED,
            ScreeningStatus::FAILED,
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
            ScreeningStatus::READY => ScreeningStatus::READY_TEXT,
            ScreeningStatus::QUEUED => ScreeningStatus::QUEUED_TEXT,
            ScreeningStatus::CALLING => ScreeningStatus::CALLING_TEXT,
            ScreeningStatus::COMPLETED => ScreeningStatus::COMPLETED_TEXT,
            ScreeningStatus::NOT_ANSWERED => ScreeningStatus::NOT_ANSWERED_TEXT,
            ScreeningStatus::FAILED => ScreeningStatus::FAILED_TEXT,
        ];

        if (self::isValidType($type)) {
            return $types[StringUtil::lowercase($type)];
        }

        return '';
    }
}

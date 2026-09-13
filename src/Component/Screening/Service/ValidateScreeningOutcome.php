<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Screening\Service;

use KejawenLab\Application\SemartHris\Component\Screening\ScreeningOutcome;
use KejawenLab\Application\SemartHris\Component\ValidateTypeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidateScreeningOutcome implements ValidateTypeInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public static function isValidType(string $type): bool
    {
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
            ScreeningOutcome::QUALIFIED,
            ScreeningOutcome::MAYBE,
            ScreeningOutcome::NOT_FIT,
            ScreeningOutcome::INCOMPLETE,
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
            ScreeningOutcome::QUALIFIED => ScreeningOutcome::QUALIFIED_TEXT,
            ScreeningOutcome::MAYBE => ScreeningOutcome::MAYBE_TEXT,
            ScreeningOutcome::NOT_FIT => ScreeningOutcome::NOT_FIT_TEXT,
            ScreeningOutcome::INCOMPLETE => ScreeningOutcome::INCOMPLETE_TEXT,
        ];

        if (self::isValidType($type)) {
            return $types[$type];
        }

        return '';
    }
}

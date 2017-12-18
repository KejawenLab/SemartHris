<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Service;

use KejawenLab\Application\SemartHris\Component\Salary\StateType;
use KejawenLab\Application\SemartHris\Component\ValidateTypeInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidateStateType implements ValidateTypeInterface
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
        return [StateType::STATE_PLUS, StateType::STATE_MINUS];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public static function convertToText(string $type): string
    {
        $types = [StateType::STATE_MINUS => StateType::STATE_MINUS_TEXT, StateType::STATE_PLUS => StateType::STATE_PLUS_TEXT];

        if (self::isValidType($type)) {
            return $types[$type];
        }

        return '';
    }
}

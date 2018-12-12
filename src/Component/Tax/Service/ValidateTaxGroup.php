<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Service;

use KejawenLab\Application\SemartHris\Component\Tax\TaxGroup;
use KejawenLab\Application\SemartHris\Component\ValidateTypeInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidateTaxGroup implements ValidateTypeInterface
{
    /**
     * @param string $group
     *
     * @return bool
     */
    public static function isValidType(string $group): bool
    {
        $group = StringUtil::lowercase($group);
        if (!in_array($group, self::getTypes())) {
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
            TaxGroup::TAX_TK_3,
            TaxGroup::TAX_TK_2,
            TaxGroup::TAX_TK_1,
            TaxGroup::TAX_TK_0,
            TaxGroup::TAX_K_0,
            TaxGroup::TAX_K_1,
            TaxGroup::TAX_K_2,
            TaxGroup::TAX_K_3,
            TaxGroup::TAX_KI_0,
            TaxGroup::TAX_KI_1,
            TaxGroup::TAX_KI_2,
            TaxGroup::TAX_KI_3,
        ];
    }

    /**
     * @param string $group
     *
     * @return string
     */
    public static function convertToText(string $group): string
    {
        if (self::isValidType($group)) {
            return StringUtil::uppercase($group);
        }

        return '';
    }
}

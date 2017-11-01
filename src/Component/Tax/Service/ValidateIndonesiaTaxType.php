<?php

namespace KejawenLab\Application\SemartHris\Component\Tax\Service;

use KejawenLab\Application\SemartHris\Component\Tax\IndonesianTaxType;
use KejawenLab\Application\SemartHris\Component\ValidateTypeInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class ValidateIndonesiaTaxType implements ValidateTypeInterface
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
            IndonesianTaxType::TAX_TK_3,
            IndonesianTaxType::TAX_TK_2,
            IndonesianTaxType::TAX_TK_1,
            IndonesianTaxType::TAX_TK_0,
            IndonesianTaxType::TAX_K_0,
            IndonesianTaxType::TAX_K_1,
            IndonesianTaxType::TAX_K_2,
            IndonesianTaxType::TAX_K_3,
            IndonesianTaxType::TAX_KI_0,
            IndonesianTaxType::TAX_KI_1,
            IndonesianTaxType::TAX_KI_2,
            IndonesianTaxType::TAX_KI_3,
        ])) {
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
            IndonesianTaxType::TAX_TK_3,
            IndonesianTaxType::TAX_TK_2,
            IndonesianTaxType::TAX_TK_1,
            IndonesianTaxType::TAX_TK_0,
            IndonesianTaxType::TAX_K_0,
            IndonesianTaxType::TAX_K_1,
            IndonesianTaxType::TAX_K_2,
            IndonesianTaxType::TAX_K_3,
            IndonesianTaxType::TAX_KI_0,
            IndonesianTaxType::TAX_KI_1,
            IndonesianTaxType::TAX_KI_2,
            IndonesianTaxType::TAX_KI_3,
        ];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public static function convertToText(string $type): string
    {
        if (self::isValidType($type)) {
            return StringUtil::uppercase($type);
        }

        return '';
    }
}

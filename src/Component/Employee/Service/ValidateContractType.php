<?php

namespace KejawenLab\Application\SemartHris\Component\Employee\Service;

use KejawenLab\Application\SemartHris\Component\Employee\ContractType;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class ValidateContractType
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
            ContractType::INTERSHIP,
            ContractType::OUTSOURCE,
            ContractType::PERMANENT,
            ContractType::TEMPORARY,
        ])) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public static function getContractTypes()
    {
        return [
            ContractType::INTERSHIP,
            ContractType::OUTSOURCE,
            ContractType::PERMANENT,
            ContractType::TEMPORARY,
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
            ContractType::INTERSHIP => ContractType::INTERSHIP_TEXT,
            ContractType::OUTSOURCE => ContractType::OUTSOURCE_TEXT,
            ContractType::PERMANENT => ContractType::PERMANENT_TEXT,
            ContractType::TEMPORARY => ContractType::TEMPORARY_TEXT,
        ];

        if (self::isValidType($type)) {
            return $types[$type];
        }

        return '';
    }
}

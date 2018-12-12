<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Contract\Service;

use KejawenLab\Application\SemartHris\Component\Contract\ContractType;
use KejawenLab\Application\SemartHris\Component\ValidateTypeInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidateContractType implements ValidateTypeInterface
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
        return [ContractType::CONTRACT_EMPLOYEE, ContractType::CONTRACT_CLIENT];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public static function convertToText(string $type): string
    {
        $types = [ContractType::CONTRACT_EMPLOYEE => ContractType::CONTRACT_EMPLOYEE_TEXT, ContractType::CONTRACT_CLIENT => ContractType::CONTRACT_CLIENT_TEXT];

        if (self::isValidType($type)) {
            return $types[$type];
        }

        return '';
    }
}

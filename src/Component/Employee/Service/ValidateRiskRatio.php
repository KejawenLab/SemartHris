<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Employee\Service;

use KejawenLab\Application\SemartHris\Component\Employee\RiskRatio;
use KejawenLab\Application\SemartHris\Component\ValidateTypeInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidateRiskRatio implements ValidateTypeInterface
{
    /**
     * @param string $riskRatio
     *
     * @return bool
     */
    public static function isValidType(string $riskRatio): bool
    {
        $riskRatio = StringUtil::lowercase($riskRatio);
        if (!in_array($riskRatio, self::getTypes())) {
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
            RiskRatio::RISK_VERY_HIGH,
            RiskRatio::RISK_HIGH,
            RiskRatio::RISK_NORMAL,
            RiskRatio::RISK_LOW,
            RiskRatio::RISK_VERY_LOW,
        ];
    }

    /**
     * @param string $riskRatio
     *
     * @return string
     */
    public static function convertToText(string $riskRatio): string
    {
        $riskRatios = [
            RiskRatio::RISK_VERY_HIGH => RiskRatio::RISK_VERY_HIGH_TEXT,
            RiskRatio::RISK_HIGH => RiskRatio::RISK_HIGH_TEXT,
            RiskRatio::RISK_NORMAL => RiskRatio::RISK_NORMAL_TEXT,
            RiskRatio::RISK_LOW => RiskRatio::RISK_LOW_TEXT,
            RiskRatio::RISK_VERY_LOW => RiskRatio::RISK_VERY_LOW_TEXT,
        ];

        if (self::isValidType($riskRatio)) {
            return $riskRatios[$riskRatio];
        }

        return '';
    }
}

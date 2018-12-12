<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Employee\Service;

use KejawenLab\Application\SemartHris\Component\Employee\RiskRatio;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RiskRatioConverter
{
    /**
     * @param string $riskRatioCode
     *
     * @return float
     */
    public static function getValue(string $riskRatioCode): float
    {
        $map = [
            RiskRatio::RISK_VERY_LOW => RiskRatio::RISK_VERY_LOW_VALUE,
            RiskRatio::RISK_LOW => RiskRatio::RISK_LOW_VALUE,
            RiskRatio::RISK_NORMAL => RiskRatio::RISK_NORMAL_VALUE,
            RiskRatio::RISK_HIGH => RiskRatio::RISK_HIGH_VALUE,
            RiskRatio::RISK_VERY_HIGH => RiskRatio::RISK_VERY_HIGH_VALUE,
        ];

        if (!in_array($riskRatioCode, $map)) {
            return RiskRatio::RISK_VERY_LOW_VALUE;
        }

        return $map[$riskRatioCode];
    }
}

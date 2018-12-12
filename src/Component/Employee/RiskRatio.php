<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Employee;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RiskRatio
{
    const RISK_VERY_HIGH = 'vhr';
    const RISK_HIGH = 'hr';
    const RISK_NORMAL = 'nr';
    const RISK_LOW = 'lr';
    const RISK_VERY_LOW = 'vlr';

    const RISK_VERY_HIGH_TEXT = 'RESIKO SANGAT TINGGI';
    const RISK_HIGH_TEXT = 'RESIKO TINGGI';
    const RISK_NORMAL_TEXT = 'RESIKO NORMAL';
    const RISK_LOW_TEXT = 'RESIKO RENDAH';
    const RISK_VERY_LOW_TEXT = 'RESIKO SANGAT RENDAH';

    const RISK_VERY_HIGH_VALUE = 0.0174;
    const RISK_HIGH_VALUE = 0.0127;
    const RISK_NORMAL_VALUE = 0.0089;
    const RISK_LOW_VALUE = 0.0054;
    const RISK_VERY_LOW_VALUE = 0.0024;
}

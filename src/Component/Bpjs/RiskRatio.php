<?php
/**
 * This file is part of the Semart HRIS Application.
 *
 * (c) Muhamad Surya Iksanudin <surya.kejawen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Bpjs;

use KejawenLab\Semart\Skeleton\Component\Contract\Util\AbstractType;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
final class RiskRatio extends AbstractType
{
    public const RISK_VERY_HIGH = 'vhr';
    public const RISK_HIGH = 'hr';
    public const RISK_NORMAL = 'nr';
    public const RISK_LOW = 'lr';
    public const RISK_VERY_LOW = 'vlr';

    public const RISK_VERY_HIGH_TEXT = 'RESIKO SANGAT TINGGI';
    public const RISK_HIGH_TEXT = 'RESIKO TINGGI';
    public const RISK_NORMAL_TEXT = 'RESIKO NORMAL';
    public const RISK_LOW_TEXT = 'RESIKO RENDAH';
    public const RISK_VERY_LOW_TEXT = 'RESIKO SANGAT RENDAH';

    public const RISK_VERY_HIGH_VALUE = 0.0174;
    public const RISK_HIGH_VALUE = 0.0127;
    public const RISK_NORMAL_VALUE = 0.0089;
    public const RISK_LOW_VALUE = 0.0054;
    public const RISK_VERY_LOW_VALUE = 0.0024;

    /**
     * @return array
     */
    public function getValues(): array
    {
        return [
            self::RISK_VERY_HIGH => self::RISK_VERY_HIGH_TEXT,
            self::RISK_HIGH => self::RISK_HIGH_TEXT,
            self::RISK_LOW => self::RISK_LOW_TEXT,
            self::RISK_VERY_LOW => self::RISK_VERY_LOW_TEXT,
        ];
    }

    public function getCalculationValue(string $key): float
    {
        $mapping = [
            self::RISK_VERY_HIGH => self::RISK_VERY_HIGH_VALUE,
            self::RISK_HIGH => self::RISK_HIGH_VALUE,
            self::RISK_LOW => self::RISK_LOW_VALUE,
            self::RISK_VERY_LOW => self::RISK_VERY_LOW_VALUE,
        ];

        if (in_array($key, $mapping)) {
            return $mapping[$key];
        }

        throw new RiskRatioNotQualifiedException();
    }
}

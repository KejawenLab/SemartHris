<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class TaxGroup
{
    const TAX_TK_0 = 'tk0';
    const TAX_TK_1 = 'tk1';
    const TAX_TK_2 = 'tk2';
    const TAX_TK_3 = 'tk3';
    const TAX_K_0 = 'k0';
    const TAX_K_1 = 'k1';
    const TAX_K_2 = 'k2';
    const TAX_K_3 = 'k3';
    const TAX_KI_0 = 'ki0';
    const TAX_KI_1 = 'ki1';
    const TAX_KI_2 = 'ki2';
    const TAX_KI_3 = 'ki3';

    /**
     * @var array
     *
     * @see https://www.online-pajak.com/id/ptkp-terbaru-pph-21
     */
    public static $PTKP = [
        self::TAX_TK_0 => 54000000, //54jt
        self::TAX_TK_1 => 58500000,
        self::TAX_TK_2 => 63000000,
        self::TAX_TK_3 => 67500000,
        self::TAX_K_0 => 58500000,
        self::TAX_K_1 => 63000000,
        self::TAX_K_2 => 67500000,
        self::TAX_K_3 => 72000000,
        self::TAX_KI_0 => 112500000,
        self::TAX_KI_1 => 117000000,
        self::TAX_KI_2 => 121500000,
        self::TAX_KI_3 => 126000000,
    ];

    /**
     * @param null|string $group
     *
     * @return bool
     */
    public static function isTKGroup(?string $group): bool
    {
        switch ($group) {
            case self::TAX_TK_0:
            case self::TAX_TK_1:
            case self::TAX_TK_2:
            case self::TAX_TK_3:
                return true;
                break;
        }

        return false;
    }

    /**
     * @param null|string $group
     *
     * @return bool
     */
    public static function isKIGroup(?string $group): bool
    {
        switch ($group) {
            case self::TAX_KI_0:
            case self::TAX_KI_1:
            case self::TAX_KI_2:
            case self::TAX_KI_3:
                return true;
                break;
        }

        return false;
    }

    /**
     * @param null|string $group
     *
     * @return bool
     */
    public static function isKGroup(?string $group): bool
    {
        switch ($group) {
            case self::TAX_K_0:
            case self::TAX_K_1:
            case self::TAX_K_2:
            case self::TAX_K_3:
                return true;
                break;
        }

        return false;
    }
}

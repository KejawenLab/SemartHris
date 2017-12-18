<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Util;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MonthUtil
{
    private static $months = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    /**
     * @return array
     */
    public static function getMonths(): array
    {
        return self::$months;
    }

    /**
     * @param int $month
     *
     * @return null|string
     */
    public static function convertToText(int $month): ? string
    {
        if (isset(self::$months[$month])) {
            return self::$months[$month];
        }

        return null;
    }
}

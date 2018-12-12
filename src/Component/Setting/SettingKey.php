<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Setting;

use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SettingKey
{
    const PREFIX = 'SEMART_';
    const DATE_FORMAT = 'DATE_FORMAT';
    const DATE_QUERY_FORMAT = 'QUERY_DATE_FORMAT';
    const TIME_FORMAT = 'TIME_FORMAT';
    const HOUR_FORMAT = 'HOUR_FORMAT';
    const DEFAULT_PASSWORD = 'DEFAULT_PASSWORD';
    const DATE_TIME_FORMAT = 'DATE_TIME_FORMAT';
    const FIRST_DATE_FORMAT = 'FIRST_DATE_FORMAT';
    const LAST_DATE_FORMAT = 'LAST_DATE_FORMAT';
    const UPLOAD_DESTINATION = 'UPLOAD_DESTINATION';
    const ATTENDANCE_UPLOAD_PATH = 'ATTENDANCE_UPLOAD_PATH';
    const OVERTIME_INVALID_MESSAGE = 'OVERTIME_INVALID_MESSAGE';
    const OVERTIME_UPLOAD_PATH = 'OVERTIME_UPLOAD_PATH';
    const OVERTIME_COMPONENT_CODE = 'OVERTIME_BENEFIT_CODE';
    const JKK_COMPONENT_CODE = 'PAYROLL_BPJS_JKK_CODE';
    const JKM_COMPONENT_CODE = 'PAYROLL_BPJS_JKM_CODE';
    const JHTP_COMPONENT_CODE = 'PAYROLL_BPJS_JHT_CODE_PLUS';
    const JHTM_COMPONENT_CODE = 'PAYROLL_BPJS_JHT_CODE_MINUS';
    const JHTC_COMPONENT_CODE = 'PAYROLL_BPJS_JHT_CODE_COMPANY';
    const JPP_COMPONENT_CODE = 'PAYROLL_BPJS_JP_CODE_PLUS';
    const JPM_COMPONENT_CODE = 'PAYROLL_BPJS_JP_CODE_MINUS';
    const JPC_COMPONENT_CODE = 'PAYROLL_BPJS_JP_CODE_COMPANY';
    const PPH21P_COMPONENT_CODE = 'TAX_PLUS_CODE';
    const PPH21M_COMPONENT_CODE = 'TAX_MINUS_CODE';
    const SECURITY_ATTENDANCE_MENU = 'SECURITY_ATTENDANCE_MENU';
    const SECURITY_PAYROLL_MENU = 'SECURITY_PAYROLL_MENU';
    const SECURITY_OVERTIME_MENU = 'SECURITY_OVERTIME_MENU';

    /**
     * @param string $key
     * @param bool   $removePrefix
     *
     * @return string
     */
    public static function getRealKey(string $key, bool $removePrefix = false): string
    {
        $key = StringUtil::uppercase($key);
        if (false === strpos($key, sprintf('%s', self::PREFIX))) {
            $key = sprintf('%s%s', self::PREFIX, $key);
        }

        if ($removePrefix) {
            $key = str_replace(self::PREFIX, '', $key);
        }

        return $key;
    }
}

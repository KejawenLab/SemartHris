<?php

namespace KejawenLab\Application\SemartHris\Util;

use KejawenLab\Application\SemartHris\Component\Setting\Setting as Base;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SettingUtil
{
    const DATE_FORMAT = 'date_format';
    const DATE_QUERY_FORMAT = 'query_date_format';
    const TIME_FORMAT = 'time_format';
    const HOUR_FORMAT = 'hour_format';
    const DEFAULT_PASSWORD = 'default_password';
    const DATE_TIME_FORMAT = 'date_time_format';
    const FIRST_DATE_FORMAT = 'first_date_format';
    const LAST_DATE_FORMAT = 'last_date_format';
    const UPDATE_DESTIONATION = 'upload_destination';
    const ATTENDANCE_UPLOAD_PATH = 'attendance_upload_path';
    const OVERTIME_INVALID_MESSAGE = 'overtime_invalid_message';
    const OVERTIME_UPLOAD_PATH = 'overtime_upload_path';
    const OVERTIME_COMPONENT_CODE = 'overtime_benefit_code';
    const SECURITY_ATTENDANCE_MENU = 'security_attendance_menu';
    const SECURITY_PAYROLL_MENU = 'security_payroll_menu';
    const SECURITY_OVERTIME_MENU = 'security_overtime_menu';

    public static function get(string $key)
    {
        return Base::get(Base::getKey($key));
    }
}

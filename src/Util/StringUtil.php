<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Util;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class StringUtil
{
    /**
     * @param string $value
     *
     * @return string
     */
    public static function uppercase(string $value): string
    {
        return strtoupper($value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public static function lowercase(string $value): string
    {
        return strtolower($value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public static function underscore(string $value): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', str_replace(' ', '_', $value)));
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public static function dash(string $value): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', str_replace(' ', '-', $value)));
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public static function camelcase(string $value): string
    {
        return preg_replace_callback('/_([a-z])/', function ($match) {
            return strtoupper($match[1]);
        }, strtolower($value));
    }

    /**
     * @param null|string $prefix
     *
     * @return string
     */
    public static function randomize(?string $prefix): string
    {
        return sha1(uniqid($prefix, true));
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public static function sanitize(string $value): string
    {
        return trim($value);
    }
}

<?php

namespace Persona\Hris\Util;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@hrpersona.id>
 */
final class StringUtil
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
     * @param null $prefix
     *
     * @return string
     */
    public static function randomize($prefix = null): string
    {
        return sha1(uniqid($prefix, true));
    }
}

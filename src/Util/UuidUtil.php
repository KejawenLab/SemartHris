<?php

namespace KejawenLab\Application\SemartHris\Util;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class UuidUtil
{
    /**
     * @param null|string $uuid
     *
     * @return bool
     */
    public static function isValid(?string $uuid): bool
    {
        if (preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $uuid)) {
            return true;
        }

        return false;
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Util;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
        if (preg_match('/^\{?[A-Za-z0-9]{8}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{12}\}?$/', $uuid)) {
            return true;
        }

        return false;
    }
}

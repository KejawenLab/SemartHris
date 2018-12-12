<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface ValidateTypeInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public static function isValidType(string $type): bool;

    /**
     * @return array
     */
    public static function getTypes(): array;

    /**
     * @param string $type
     *
     * @return string
     */
    public static function convertToText(string $type): string;
}

<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Screening;

/**
 * Masks candidate phone numbers in lists and tables.
 * Ported from HireCall candidates/page.tsx maskPhone.
 * Full numbers are only shown inside the call confirmation modal.
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PhoneMasker
{
    /**
     * @param null|string $phone E.164 phone number, e.g. +6281234567890
     *
     * @return null|string Masked number, e.g. +628 .... 7890
     */
    public static function mask(?string $phone): ?string
    {
        if (null === $phone || '' === $phone || strlen($phone) < 8) {
            return $phone;
        }

        return sprintf('%s .... %s', substr($phone, 0, 4), substr($phone, -4));
    }
}

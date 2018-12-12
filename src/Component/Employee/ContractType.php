<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Employee;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ContractType
{
    const PERMANENT = 'p';
    const TEMPORARY = 't';
    const OUTSOURCE = 'o';
    const INTERSHIP = 'i';

    const PERMANENT_TEXT = 'KARYAWAN TETAP';
    const TEMPORARY_TEXT = 'KARYAWAN KONTRAK';
    const OUTSOURCE_TEXT = 'KARYAWAN OUTSOURCE';
    const INTERSHIP_TEXT = 'KARYAWAN MAGANG';
}

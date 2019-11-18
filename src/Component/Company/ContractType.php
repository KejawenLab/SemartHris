<?php
/**
 * This file is part of the Semart HRIS Application.
 *
 * (c) Muhamad Surya Iksanudin <surya.kejawen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Company;

use KejawenLab\Semart\Skeleton\Component\Contract\Util\AbstractType;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
final class ContractType extends AbstractType
{
    public const PERMANENT = 'p';
    public const TEMPORARY = 't';
    public const OUTSOURCE = 'o';
    public const INTERSHIP = 'i';

    public const PERMANENT_TEXT = 'KARYAWAN TETAP';
    public const TEMPORARY_TEXT = 'KARYAWAN KONTRAK';
    public const OUTSOURCE_TEXT = 'KARYAWAN OUTSOURCE';
    public const INTERSHIP_TEXT = 'KARYAWAN MAGANG';

    /**
     * @return array
     */
    public function getValues(): array
    {
        return [
            self::PERMANENT => self::PERMANENT_TEXT,
            self::TEMPORARY => self::TEMPORARY_TEXT,
            self::OUTSOURCE => self::OUTSOURCE_TEXT,
            self::INTERSHIP => self::INTERSHIP_TEXT,
        ];
    }
}

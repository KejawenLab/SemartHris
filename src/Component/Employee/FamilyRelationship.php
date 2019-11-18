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

namespace KejawenLab\Semart\Skeleton\Component\Employee;

use KejawenLab\Semart\Skeleton\Component\Contract\Util\AbstractType;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
final class FamilyRelationship extends AbstractType
{
    public const PARENT = 'p';
    public const COUPLE = 'c';
    public const SON = 's';

    public const PARENT_TEXT = 'ORANG TUA';
    public const COUPLE_TEXT = 'SUAMI/ISTRI';
    public const SON_TEXT = 'ANAK';

    /**
     * @return array
     */
    public function getValues(): array
    {
        return [
            self::PARENT => self::PARENT_TEXT,
            self::COUPLE => self::COUPLE_TEXT,
            self::SON => self::SON_TEXT,
        ];
    }
}

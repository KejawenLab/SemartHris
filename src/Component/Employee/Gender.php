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
final class Gender extends AbstractType
{
    public const MALE = 'm';
    public const FEMALE = 'f';

    public const MALE_TEXT = 'PRIA';
    public const FEMALE_TEXT = 'WANITA';

    /**
     * @return array
     */
    public function getValues(): array
    {
        return [
            self::MALE => self::MALE_TEXT,
            self::FEMALE => self::FEMALE_TEXT,
        ];
    }
}

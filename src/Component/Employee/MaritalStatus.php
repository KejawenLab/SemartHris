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
final class MaritalStatus extends AbstractType
{
    public const SINGLE = 's';
    public const MARRIED = 'm';
    public const DISVORCE = 'd';

    public const SINGLE_TEXT = 'BELUM KAWIN';
    public const MARRIED_TEXT = 'KAWIN';
    public const DISVORCE_TEXT = 'CERAI';

    /**
     * @return array
     */
    public function getValues(): array
    {
        return [
            self::SINGLE => self::SINGLE_TEXT,
            self::MARRIED => self::MARRIED_TEXT,
            self::DISVORCE => self::DISVORCE_TEXT,
        ];
    }
}

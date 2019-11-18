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
final class IdentityType extends AbstractType
{
    public const DRIVER_LISENCE = 's';
    public const PASSPORT = 'p';
    public const ID_CARD = 'k';

    public const DRIVER_LISENCE_TEXT = 'SIM';
    public const PASSPORT_TEXT = 'PASPOR';
    public const ID_CARD_TEXT = 'KTP';

    /**
     * @return array
     */
    public function getValues(): array
    {
        return [
            self::DRIVER_LISENCE => self::DRIVER_LISENCE_TEXT,
            self::PASSPORT => self::PASSPORT_TEXT,
            self::ID_CARD => self::ID_CARD_TEXT,
        ];
    }
}

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

namespace KejawenLab\Semart\Skeleton\Component\Address;

use KejawenLab\Nusantara\Nusantara;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AddressUpdaterService
{
    private $province;

    private $district;

    private $subDistrict;

    public function __construct()
    {
    }

    public function update()
    {
        $nusantara = new Nusantara();
        $results = $nusantara->fetch(Nusantara::SCOPE_KECAMATAN);
    }
}

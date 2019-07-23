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

namespace KejawenLab\Semart\Skeleton\Component\Contract\Company;

use KejawenLab\Semart\Skeleton\Component\Contract\Address\DistrictInterface;
use KejawenLab\Semart\Skeleton\Component\Contract\Address\ProvinceInterface;
use KejawenLab\Semart\Skeleton\Component\Contract\Address\SubDistrictInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface CompanyInterface
{
    public function getCode(): ?string;

    public function getName(): ?string;

    public function getLogo(): ?string;

    public function getBirtDay(): ?\DateTime;

    public function getAddress(): ?string;

    public function getSubDistrict(): ?SubDistrictInterface;

    public function getDistrict(): ?DistrictInterface;

    public function getProvince(): ?ProvinceInterface;

    public function getEmail(): ?string;

    public function getTaxNumber(): ?string;
}

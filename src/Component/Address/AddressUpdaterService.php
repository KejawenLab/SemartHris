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
use KejawenLab\Semart\Collection\Collection;
use KejawenLab\Semart\Skeleton\Entity\District;
use KejawenLab\Semart\Skeleton\Entity\Province;
use KejawenLab\Semart\Skeleton\Entity\SubDistrict;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AddressUpdaterService
{
    private $provinceService;

    private $districtService;

    private $subDistrictService;

    public function __construct(ProvinceService $provinceService, DistrictService $districtService, SubDistrictService $subDistrictService)
    {
        $this->provinceService = $provinceService;
        $this->districtService = $districtService;
        $this->subDistrictService = $subDistrictService;
    }

    public function update(OutputInterface $output = null)
    {
        $pidx = 0;
        $didx = 0;
        $sidx = 0;
        $nusantara = new Nusantara();
        $results = $nusantara->fetch(Nusantara::SCOPE_KECAMATAN, $output);
        if ($output) {
            $output->writeln('<info>Menyimpan ke database (<comment>memerlukan waktu beberapa menit hingga beberapa jam tergantung koneksi ke database</comment>).</info>');
        }
        Collection::collect($results)
            ->each(function ($value, $code) use (&$pidx, &$didx, &$sidx) {
                $province = $this->provinceService->getByCode((string) $code);
                if (!$province) {
                    $province = new Province();
                    $province->setCode((string) $code);
                }
                $province->setName($value['name']);

                if (0 === $pidx % 17) {
                    $this->provinceService->commit($province, true);
                } else {
                    $this->provinceService->commit($province);
                }

                Collection::collect($value['district'])
                    ->each(function ($value, $code) use ($province, &$didx, &$sidx) {
                        $district = $this->districtService->getByCode((string) $code);
                        if (!$district) {
                            $district = new District();
                            $district->setCode((string) $code);
                        }
                        $district->setProvince($province);
                        $district->setName($value['name']);

                        if (0 === $didx % 17) {
                            $this->districtService->commit($district, true);
                        } else {
                            $this->districtService->commit($district);
                        }

                        Collection::collect($value['sub_district'])
                            ->each(function ($value, $code) use ($district, &$sidx) {
                                $subDistrict = $this->subDistrictService->getByCode((string) $code);
                                if (!$subDistrict) {
                                    $subDistrict = new SubDistrict();
                                    $subDistrict->setCode((string) $code);
                                }
                                $subDistrict->setDistrict($district);
                                $subDistrict->setName($value['name']);

                                if (0 === $sidx % 17) {
                                    $this->subDistrictService->commit($subDistrict, true);
                                } else {
                                    $this->subDistrictService->commit($subDistrict);
                                }
                            })
                        ;
                    })
                ;
            })
        ;
    }
}

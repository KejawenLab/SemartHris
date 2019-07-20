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

use KejawenLab\Semart\Skeleton\Component\Contract\Address\SubDistrictInterface;
use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\SubDistrict;
use KejawenLab\Semart\Skeleton\Repository\SubDistrictRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SubDistrictService implements ServiceInterface
{
    private $subdistrictRepository;

    public function __construct(SubDistrictRepository $subdistrictRepository)
    {
        $subdistrictRepository->setCacheable(true);
        $this->subdistrictRepository = $subdistrictRepository;
    }

    /**
     * @param string $id
     *
     * @return SubDistrict|null
     */
    public function get(string $id): ?object
    {
        return $this->subdistrictRepository->find($id);
    }

    public function commit(SubDistrictInterface $subDistrict, bool $flush = false): void
    {
        $this->subdistrictRepository->commit($subDistrict);
        if ($flush) {
            $this->subdistrictRepository->flush();
        }
    }
}

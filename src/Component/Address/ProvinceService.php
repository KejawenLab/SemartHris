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

use KejawenLab\Semart\Skeleton\Component\Contract\Address\ProvinceInterface;
use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\Province;
use KejawenLab\Semart\Skeleton\Repository\ProvinceRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ProvinceService implements ServiceInterface
{
    private $provinceRepository;

    public function __construct(ProvinceRepository $provinceRepository)
    {
        $provinceRepository->setCacheable(true);
        $this->provinceRepository = $provinceRepository;
    }

    /**
     * @param string $id
     *
     * @return Province|null
     */
    public function get(string $id): ?object
    {
        return $this->provinceRepository->find($id);
    }

    public function getByCode(string $code): ?Province
    {
        return $this->provinceRepository->findOneBy(['code' => $code]);
    }

    /**
     * @return Province[]
     */
    public function getAll(): array
    {
        return $this->provinceRepository->findAll();
    }

    public function commit(ProvinceInterface $province, bool $flush = false): void
    {
        $this->provinceRepository->commit($province);
        if ($flush) {
            $this->provinceRepository->flush();
        }
    }
}

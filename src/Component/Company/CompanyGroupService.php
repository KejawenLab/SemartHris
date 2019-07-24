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

use KejawenLab\Semart\Skeleton\Component\Contract\Company\CompanyGroupRepositoryInterface;
use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\CompanyGroup;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyGroupService implements ServiceInterface
{
    private $companygroupRepository;

    public function __construct(CompanyGroupRepositoryInterface $companygroupRepository)
    {
        $companygroupRepository->setCacheable(true);
        $this->companygroupRepository = $companygroupRepository;
    }

    /**
     * @param string $id
     *
     * @return CompanyGroup|null
     */
    public function get(string $id): ?object
    {
        return $this->companygroupRepository->find($id);
    }

    /**
     * @return CompanyGroup[]
     */
    public function getAll(): array
    {
        return $this->companygroupRepository->findAll();
    }
}

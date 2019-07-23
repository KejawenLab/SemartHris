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

use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\Company;
use KejawenLab\Semart\Skeleton\Repository\CompanyRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyService implements ServiceInterface
{
    private $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $companyRepository->setCacheable(true);
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param string $id
     *
     * @return Company|null
     */
    public function get(string $id): ?object
    {
        return $this->companyRepository->find($id);
    }
}

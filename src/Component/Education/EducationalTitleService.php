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

namespace KejawenLab\Semart\Skeleton\Component\Education;

use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\EducationalTitle;
use KejawenLab\Semart\Skeleton\Repository\EducationalTitleRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EducationalTitleService implements ServiceInterface
{
    private $educationaltitleRepository;

    public function __construct(EducationalTitleRepository $educationaltitleRepository)
    {
        $educationaltitleRepository->setCacheable(true);
        $this->educationaltitleRepository = $educationaltitleRepository;
    }

    /**
     * @param string $id
     *
     * @return EducationalTitle|null
     */
    public function get(string $id): ?object
    {
        return $this->educationaltitleRepository->find($id);
    }
}

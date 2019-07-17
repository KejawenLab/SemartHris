<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Education;

use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\EducationInstitute;
use KejawenLab\Semart\Skeleton\Repository\EducationInstituteRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EducationInstituteService implements ServiceInterface
{
    private $educationinstituteRepository;

    public function __construct(EducationInstituteRepository $educationinstituteRepository)
    {
        $educationinstituteRepository->setCacheable(true);
        $this->educationinstituteRepository = $educationinstituteRepository;
    }

    /**
     * @param string $id
     *
     * @return EducationInstitute|null
     */
    public function get(string $id): ?object
    {
        return $this->educationinstituteRepository->find($id);
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Company;

use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\CompanyLetter;
use KejawenLab\Semart\Skeleton\Repository\CompanyLetterRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyLetterService implements ServiceInterface
{
    private $companyletterRepository;

    public function __construct(CompanyLetterRepository $companyletterRepository)
    {
        $companyletterRepository->setCacheable(true);
        $this->companyletterRepository = $companyletterRepository;
    }

    /**
     * @param string $id
     *
     * @return CompanyLetter|null
     */
    public function get(string $id): ?object
    {
        return $this->companyletterRepository->find($id);
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\DataTransformer;

use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Component\Company\Repository\CompanyRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyTransformer implements DataTransformerInterface
{
    /**
     * @var CompanyRepositoryInterface
     */
    private $companyRepository;

    /**
     * @param CompanyRepositoryInterface $companyRepository
     */
    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param object $company
     *
     * @return string
     */
    public function transform($company): string
    {
        if (null === $company) {
            return '';
        }

        return $company->getId();
    }

    /**
     * @param string $companyId
     *
     * @return null|CompanyInterface
     */
    public function reverseTransform($companyId)
    {
        if (!$companyId) {
            return null;
        }

        $company = $this->companyRepository->find($companyId);
        if (null === $company) {
            throw new TransformationFailedException(sprintf('Company with id "%s" is not exist.', $companyId));
        }

        return $company;
    }
}

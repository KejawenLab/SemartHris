<?php

namespace Persona\Hris\DataTransformer;

use Persona\Hris\Component\Company\Model\CompanyInterface;
use Persona\Hris\Repository\CompanyRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
final class CompanyTransformer implements DataTransformerInterface
{
    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @param CompanyRepository $companyRepository
     */
    public function __construct(CompanyRepository $companyRepository)
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

        return $company;
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
            throw new TransformationFailedException(sprintf('Company with id %s is not exist.', $companyId));
        }

        return $company;
    }
}

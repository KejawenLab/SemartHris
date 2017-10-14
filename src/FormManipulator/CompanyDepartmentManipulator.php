<?php

namespace KejawenLab\Application\SemartHris\FormManipulator;

use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class CompanyDepartmentManipulator implements FormManipulatorInterface
{
    /**
     * @var DataTransformerInterface
     */
    private $companyTransformer;

    /**
     * @param DataTransformerInterface $companyTransformer
     */
    public function __construct(DataTransformerInterface $companyTransformer)
    {
        $this->companyTransformer = $companyTransformer;
    }

    /**
     * @param FormBuilderInterface $formBuilder
     * @param mixed                $entity
     *
     * @return FormBuilderInterface
     */
    public function manipulate(FormBuilderInterface $formBuilder, $entity): FormBuilderInterface
    {
        $company = $formBuilder->get('company');
        $company->addModelTransformer($this->companyTransformer);
        /** @var CompanyInterface $companyEntity */
        if ($companyEntity = $entity->getCompany()) {
            $company->setData($companyEntity->getId());

            $formBuilder->get('company_readonly')->setData($companyEntity);
        }

        return $formBuilder;
    }
}

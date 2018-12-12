<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\Manipulator;

use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyDepartmentManipulator extends FormManipulator implements FormManipulatorInterface
{
    /**
     * @param FormBuilderInterface $formBuilder
     * @param mixed                $entity
     *
     * @return FormBuilderInterface
     */
    public function manipulate(FormBuilderInterface $formBuilder, $entity): FormBuilderInterface
    {
        $formBuilder = parent::manipulate($formBuilder, $entity);

        /** @var CompanyInterface $companyEntity */
        if ($companyEntity = $entity->getCompany()) {
            $formBuilder->get('company')->setData($companyEntity->getId());
            $formBuilder->get('company_readonly')->setData($companyEntity);
        }

        return $formBuilder;
    }
}

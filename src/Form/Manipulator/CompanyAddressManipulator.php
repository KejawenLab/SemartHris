<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\Manipulator;

use KejawenLab\Application\SemartHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyAddressManipulator extends FormManipulator implements FormManipulatorInterface
{
    /**
     * @var DataTransformerInterface
     */
    private $companyTransformer;

    /**
     * @param DataTransformerInterface $transformer
     * @param array                    $dataTransformers
     * @param array                    $eventSubscribers
     */
    public function __construct(DataTransformerInterface $transformer, $dataTransformers = [], $eventSubscribers = [])
    {
        parent::__construct($dataTransformers, $eventSubscribers);
        $this->companyTransformer = $transformer;
    }

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
            $formBuilder->remove('company');

            $formBuilder->add('company', HiddenType::class);
            $company = $formBuilder->get('company');
            $company->addModelTransformer($this->companyTransformer);
            $company->setData($companyEntity->getId());

            $formBuilder->get('company_readonly')->setData($companyEntity);
        } else {
            $formBuilder->remove('company_readonly');
        }

        /** @var CityInterface $cityEntity */
        if ($cityEntity = $entity->getCity()) {
            $formBuilder->get('city')->setData($cityEntity->getId());
        }

        return $formBuilder;
    }
}

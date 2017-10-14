<?php

namespace KejawenLab\Application\SemartHris\FormManipulator;

use KejawenLab\Application\SemartHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemartHris\Component\Address\Repository\CityRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Address\Service\DefaultAddressChecker;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class CompanyAddressManipulator implements FormManipulatorInterface
{
    /**
     * @var DataTransformerInterface
     */
    private $companyTransformer;

    /**
     * @var DataTransformerInterface
     */
    private $cityTransformer;

    /**
     * @var DefaultAddressChecker
     */
    private $defaultAddressChecker;

    /**
     * @var CityRepositoryInterface
     */
    private $cityRepository;

    /**
     * @param DataTransformerInterface $companyTransformer
     * @param DataTransformerInterface $cityTransformer
     * @param DefaultAddressChecker    $defaultAddressChecker
     * @param CityRepositoryInterface  $cityRepository
     */
    public function __construct(
        DataTransformerInterface $companyTransformer,
        DataTransformerInterface $cityTransformer,
        DefaultAddressChecker $defaultAddressChecker,
        CityRepositoryInterface $cityRepository
    ) {
        $this->companyTransformer = $companyTransformer;
        $this->cityTransformer = $cityTransformer;
        $this->defaultAddressChecker = $defaultAddressChecker;
        $this->cityRepository = $cityRepository;
    }

    /**
     * @param FormBuilderInterface $formBuilder
     * @param mixed                $entity
     *
     * @return FormBuilderInterface
     */
    public function manipulate(FormBuilderInterface $formBuilder, $entity): FormBuilderInterface
    {
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

        $city = $formBuilder->get('city');
        $city->addModelTransformer($this->cityTransformer);
        /** @var CityInterface $cityEntity */
        if ($cityEntity = $entity->getCity()) {
            $city->setData($cityEntity->getId());
        }

        $cityRepository = $this->cityRepository;
        $formBuilder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($cityRepository) {
            $form = $event->getForm();
            $data = $event->getData();

            if ($cityRepository->find($data['city'])) {
                $form->remove('city_text');
            }
        });

        $defaultAddressChecker = $this->defaultAddressChecker;
        $formBuilder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($defaultAddressChecker) {
            $defaultAddressChecker->unsetDefaultExcept($event->getData());
        });

        return $formBuilder;
    }
}

<?php

namespace KejawenLab\Application\SemarHris\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use KejawenLab\Application\SemarHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemarHris\Component\Address\Service\DefaultAddressChecker;
use KejawenLab\Application\SemarHris\Component\Company\Model\CompanyAddressInterface;
use KejawenLab\Application\SemarHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemarHris\DataTransformer\CityTransformer;
use KejawenLab\Application\SemarHris\DataTransformer\CompanyTransformer;
use KejawenLab\Application\SemarHris\Entity\Company;
use KejawenLab\Application\SemarHris\Entity\CompanyAddress;
use KejawenLab\Application\SemarHris\Repository\CityRepository;
use KejawenLab\Application\SemarHris\Repository\CompanyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 *
 * @Route(path="/admin")
 */
class CompanyAddressController extends AdminController
{
    /**
     * @Route(path="/company/address", name="company_address")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addressPerCompanyAction(Request $request)
    {
        $company = $this->container->get(CompanyRepository::class)->find($request->query->get('id'));
        if ($company) {
            $session = $this->get('session');
            $session->set('companyId', $company->getId());
        }

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'sortField' => 'defaultAddress',
            'sortDirection' => 'DESC',
            'entity' => 'CompanyAddress',
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    protected function editAction()
    {
        $response = parent::editAction();

        $companyAddress = $this->container->get(CompanyRepository::class)->findCompanyAddress($this->request->query->get('id'));
        if ($companyAddress) {
            $this->container->get(DefaultAddressChecker::class)->unsetDefaultExcept($companyAddress);
        }

        return $response;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function deleteAction()
    {
        $companyAddress = $this->container->get(CompanyRepository::class)->findCompanyAddress($this->request->query->get('id'));
        if ($companyAddress && 'DELETE' === $this->request->getMethod()) {
            $this->container->get(DefaultAddressChecker::class)->setRandomDefault($companyAddress);
        }

        return parent::deleteAction();
    }

    /**
     * @return CompanyAddressInterface
     */
    protected function createNewEntity(): CompanyAddressInterface
    {
        $entity = new CompanyAddress();
        if ($companyId = $this->get('session')->get('companyId')) {
            $entity->setCompany($this->container->get(CompanyRepository::class)->find($companyId));
        }

        return $entity;
    }

    /**
     * @param object $entity
     * @param string $view
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function createEntityFormBuilder($entity, $view)
    {
        $builder = parent::createEntityFormBuilder($entity, $view);
        /** @var CompanyInterface $companyEntity */
        if ($companyEntity = $entity->getCompany()) {
            $builder->remove('company');

            $builder->add('company', HiddenType::class);

            $company = $builder->get('company');
            $company->addModelTransformer($this->container->get(CompanyTransformer::class));
            $company->setData($companyEntity->getId());

            $builder->get('company_text')->setData($companyEntity);
        } else {
            $builder->remove('company_text');
        }

        $city = $builder->get('city');
        $city->addModelTransformer($this->container->get(CityTransformer::class));
        /** @var CityInterface $cityEntity */
        if ($cityEntity = $entity->getCity()) {
            $city->setData($cityEntity->getId());
        }

        $cityRepository = $this->container->get(CityRepository::class);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($cityRepository) {
            $form = $event->getForm();
            $data = $event->getData();

            if ($cityRepository->find($data['city'])) {
                $form->remove('city_text');
            }
        });

        $defaultAddressChecker = $this->container->get(DefaultAddressChecker::class);
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($defaultAddressChecker) {
            $defaultAddressChecker->unsetDefaultExcept($event->getData());
        });

        return $builder;
    }

    /**
     * @param string $entityClass
     * @param string $sortDirection
     * @param null   $sortField
     * @param null   $dqlFilter
     *
     * @return QueryBuilder
     */
    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        return $this->container->get(CompanyRepository::class)->createCompanyAddressQueryBuilder($sortField, $sortDirection, $dqlFilter, null !== $this->get('session')->get('companyId'));
    }

    /**
     * @param string $entityClass
     * @param string $searchQuery
     * @param array  $searchableFields
     * @param null   $sortField
     * @param null   $sortDirection
     * @param null   $dqlFilter
     *
     * @return QueryBuilder
     */
    protected function createSearchQueryBuilder($entityClass, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        $queryBuilder = $this->container->get(CompanyRepository::class)->createCompanyAddressQueryBuilder($sortField, $sortDirection, $dqlFilter, null !== $this->get('session')->get('companyId'));

        $queryBuilder->leftJoin('entity.region', 'region');
        $queryBuilder->orWhere('region.code LIKE :query');
        $queryBuilder->orWhere('region.name LIKE :query');
        $queryBuilder->leftJoin('entity.city', 'city');
        $queryBuilder->orWhere('city.code LIKE :query');
        $queryBuilder->orWhere('city.name LIKE :query');
        $queryBuilder->orWhere('entity.address LIKE :query');
        $queryBuilder->orWhere('entity.postalCode LIKE :query');
        $queryBuilder->orWhere('entity.phoneNumber LIKE :query');
        $queryBuilder->orWhere('entity.faxNumber LIKE :query');

        $queryBuilder->setParameter('query', sprintf('%%%s%%', $searchQuery));

        return $queryBuilder;
    }
}

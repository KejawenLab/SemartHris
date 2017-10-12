<?php

namespace KejawenLab\Application\SemarHris\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use KejawenLab\Application\SemarHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemarHris\Component\Company\Model\CompanyAddressInterface;
use KejawenLab\Application\SemarHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemarHris\DataTransformer\CityTransformer;
use KejawenLab\Application\SemarHris\DataTransformer\CompanyTransformer;
use KejawenLab\Application\SemarHris\Entity\CompanyAddress;
use KejawenLab\Application\SemarHris\Repository\CompanyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 *
 * @Route(path="/admin")
 */
class CompanyAddressController extends AdminController
{
    /**
     * @Route(path = "/company/address", name = "company_address")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addressPerCompanyAction(Request $request)
    {
        $company = $this->container->get(CompanyRepository::class)->find($request->query->get('id'));
        if (!$company) {
            throw new AccessDeniedHttpException();
        }

        $session = $this->get('session');
        $session->set('companyId', $company->getId());

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'sortField' => 'defaultAddress',
            'sortDirection' => 'DESC',
            'entity' => 'CompanyAddress',
        ));
    }

    /**
     * @return Response
     */
    protected function listAction()
    {
        $session = $this->get('session');
        if (!$session->get('companyId')) {
            return $this->redirectToRoute('easyadmin', array(
                'action' => 'list',
                'sortField' => 'name',
                'sortDirection' => 'DESC',
                'entity' => 'Company',
            ));
        }

        return parent::listAction();
    }

    /**
     * @return CompanyAddressInterface
     */
    protected function createNewEntity(): CompanyAddressInterface
    {
        if (!$companyId = $this->get('session')->get('companyId')) {
            throw new AccessDeniedHttpException();
        }

        $entity = new CompanyAddress();
        $entity->setCompany($this->container->get(CompanyRepository::class)->find($companyId));

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

        $company = $builder->get('company');
        $company->addModelTransformer($this->container->get(CompanyTransformer::class));
        /** @var CompanyInterface $companyEntity */
        if ($companyEntity = $entity->getCompany()) {
            $company->setData($companyEntity->getId());

            $builder->get('company_text')->setData($companyEntity);
        }

        $city = $builder->get('city');
        $city->addModelTransformer($this->container->get(CityTransformer::class));
        /** @var CityInterface $cityEntity */
        if ($cityEntity = $entity->getCity()) {
            $city->setData($cityEntity->getId());
        }

        $builder->addEventListener(FormEvents::PRE_SUBMIT,
        function (FormEvent $event) {
            $form = $event->getForm();
            $form->remove('city_text');
        }
    );

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
        return $this->container->get(CompanyRepository::class)->createCompanyAddressQueryBuilder($sortField, $sortDirection, $dqlFilter);
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
        return $this->container->get(CompanyRepository::class)->createCompanyAddressQueryBuilder($sortField, $sortDirection, $dqlFilter);
    }
}

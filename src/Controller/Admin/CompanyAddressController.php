<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Address\Service\DefaultAddressChecker;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyAddressInterface;
use KejawenLab\Application\SemartHris\Entity\CompanyAddress;
use KejawenLab\Application\SemartHris\Form\Manipulator\CompanyAddressManipulator;
use KejawenLab\Application\SemartHris\Repository\CompanyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyAddressController extends AdminController
{
    /**
     * @Route("/company/address", name="company_address")
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
            $session->set('companyCode', $company->getCode());
        }

        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'sortField' => 'defaultAddress',
            'sortDirection' => 'DESC',
            'entity' => 'CompanyAddress',
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    protected function editAction()
    {
        $response = parent::editAction();

        $companyAddress = $this->container->get(CompanyRepository::class)->findAddress($this->request->query->get('id'));
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
        $companyAddress = $this->container->get(CompanyRepository::class)->findAddress($this->request->query->get('id'));
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

        return $this->container->get(CompanyAddressManipulator::class)->manipulate($builder, $entity);
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
        return $this->container->get(CompanyRepository::class)->createAddressQueryBuilder($sortField, $sortDirection, $dqlFilter, null !== $this->get('session')->get('companyId'));
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
        return $this->container->get(CompanyRepository::class)->createSearchAddressQueryBuilder($searchQuery, $sortField, $sortDirection, $dqlFilter, null !== $this->get('session')->get('companyId'));
    }
}

<?php

namespace KejawenLab\Application\SemarHris\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use KejawenLab\Application\SemarHris\Component\Company\Model\CompanyDepartmentInterface;
use KejawenLab\Application\SemarHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemarHris\DataTransformer\CompanyTransformer;
use KejawenLab\Application\SemarHris\Entity\CompanyDepartment;
use KejawenLab\Application\SemarHris\Repository\CompanyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 *
 * @Route(path="/admin")
 */
class CompanyDepartmentController extends AdminController
{
    /**
     * @Route(path = "/company/department", name = "company_department")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function departmentPerCompanyAction(Request $request)
    {
        $company = $this->container->get(CompanyRepository::class)->find($request->query->get('id'));
        if (!$company) {
            throw new AccessDeniedHttpException();
        }

        $session = $this->get('session');
        $session->set('companyId', $company->getId());

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'sortField' => 'department',
            'sortDirection' => 'DESC',
            'entity' => 'CompanyDepartment',
        ));
    }

    /**
     * @return Response
     */
    protected function listAction()
    {
        $session = $this->get('session');
        if (!$session->get('company')) {
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
     * @return CompanyDepartmentInterface
     */
    protected function createNewEntity(): CompanyDepartmentInterface
    {
        if (!$companyId = $this->get('session')->get('companyId')) {
            throw new AccessDeniedHttpException();
        }

        $entity = new CompanyDepartment();
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
        return $this->container->get(CompanyRepository::class)->createCompanyDepartmentQueryBuilder($sortField, $sortDirection, $dqlFilter);
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
        return $this->container->get(CompanyRepository::class)->createCompanyDepartmentQueryBuilder($sortField, $sortDirection, $dqlFilter);
    }
}

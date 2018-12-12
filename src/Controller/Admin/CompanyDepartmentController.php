<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyDepartmentInterface;
use KejawenLab\Application\SemartHris\Entity\CompanyDepartment;
use KejawenLab\Application\SemartHris\Form\Manipulator\CompanyDepartmentManipulator;
use KejawenLab\Application\SemartHris\Repository\CompanyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyDepartmentController extends AdminController
{
    /**
     * @Route("/company/department", name="company_department")
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
        $session->set('companyCode', $company->getCode());

        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'entity' => 'CompanyDepartment',
        ]);
    }

    /**
     * @return Response
     */
    protected function listAction()
    {
        $session = $this->get('session');
        if (!$session->get('companyId')) {
            return $this->redirectToRoute('easyadmin', [
                'action' => 'list',
                'sortField' => 'name',
                'sortDirection' => 'DESC',
                'entity' => 'Company',
            ]);
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

        return $this->container->get(CompanyDepartmentManipulator::class)->manipulate($builder, $entity);
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
        return $this->container->get(CompanyRepository::class)->createDepartmentQueryBuilder($sortField, $sortDirection, $dqlFilter);
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
        return $this->container->get(CompanyRepository::class)->createSearchDepartmentQueryBuilder($searchQuery, $sortField, $sortDirection, $dqlFilter);
    }
}

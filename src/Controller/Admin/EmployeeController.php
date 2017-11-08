<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Contract\ContractType;
use KejawenLab\Application\SemartHris\Form\Manipulator\EmployeeManipulator;
use KejawenLab\Application\SemartHris\Repository\ContractRepository;
use KejawenLab\Application\SemartHris\Repository\EmployeeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class EmployeeController extends AdminController
{
    /**
     * @Route("/joblevel/{id}/supervisors", name="supervisor_by_joblevel", options={"expose"=true})
     *
     * @param string $id
     *
     * @return Response
     */
    public function findByJobLevelAction(string $id)
    {
        $employees = $this->container->get(EmployeeRepository::class)->findSupervisorByJobLevel($id);

        return new JsonResponse(['employees' => $employees]);
    }

    /**
     * @Route("/contract/employee", name="contract_employee", options={"expose"=true})
     *
     * @return Response
     */
    public function findEmployeeContractAction()
    {
        $result = [];
        $contracts = $this->container->get(ContractRepository::class)->findByType(ContractType::CONTRACT_EMPLOYEE);
        foreach ($contracts as $contract) {
            $result[] = $contract;
        }

        return new JsonResponse(['contracts' => $contracts]);
    }

    /**
     * @Route("/employee/search", name="employee_search", options={"expose"=true})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function searchEmployeesAction(Request $request)
    {
        $employees = $this->container->get(EmployeeRepository::class)->search($request);

        return new JsonResponse(['employees' => $employees]);
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

        return $this->container->get(EmployeeManipulator::class)->manipulate($builder, $entity);
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
        return $this->container->get(EmployeeRepository::class)->createEmployeeQueryBuilder($sortField, $sortDirection, $dqlFilter);
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
        return $this->container->get(EmployeeRepository::class)->createSearchEmployeeQueryBuilder($searchQuery, $sortField, $sortDirection, $dqlFilter);
    }
}

<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use KejawenLab\Application\SemartHris\Component\Contract\ContractType;
use KejawenLab\Application\SemartHris\Form\Manipulator\EmployeeManipulator;
use KejawenLab\Application\SemartHris\Repository\ContractRepository;
use KejawenLab\Application\SemartHris\Repository\EmployeeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class EmployeeController extends AdminController
{
    /**
     * @Route(path="/joblevel/{id}/supervisors", name="supervisor_by_joblevel", options={"expose"=true})
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
     * @Route(path="/contract/employee", name="contract_employee", options={"expose"=true})
     *
     * @return Response
     */
    public function findEmployeeContractAction()
    {
        $result = [];
        $employees = $this->container->get(ContractRepository::class)->findByType(ContractType::CONTRACT_EMPLOYEE);
        foreach ($employees as $employee) {
            $result[] = $employee;
        }

        return new JsonResponse(['contracts' => $employees]);
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

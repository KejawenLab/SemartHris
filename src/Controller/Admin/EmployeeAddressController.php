<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Address\Service\DefaultAddressChecker;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeAddressInterface;
use KejawenLab\Application\SemartHris\Entity\EmployeeAddress;
use KejawenLab\Application\SemartHris\Form\Manipulator\EmployeeAddressManipulator;
use KejawenLab\Application\SemartHris\Repository\EmployeeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EmployeeAddressController extends AdminController
{
    /**
     * @Route("/employee/address", name="employee_address")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addressPerEmployeeAction(Request $request)
    {
        $employee = $this->container->get(EmployeeRepository::class)->find($request->query->get('id'));
        if ($employee) {
            $session = $this->get('session');
            $session->set('employeeId', $employee->getId());
        }

        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'sortField' => 'defaultAddress',
            'sortDirection' => 'DESC',
            'entity' => 'EmployeeAddress',
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    protected function editAction()
    {
        $response = parent::editAction();

        $employeeAddress = $this->container->get(EmployeeRepository::class)->findAddress($this->request->query->get('id'));
        if ($employeeAddress) {
            $this->container->get(DefaultAddressChecker::class)->unsetDefaultExcept($employeeAddress);
        }

        return $response;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function deleteAction()
    {
        $employeeAddress = $this->container->get(EmployeeRepository::class)->findAddress($this->request->query->get('id'));
        if ($employeeAddress && 'DELETE' === $this->request->getMethod()) {
            $this->container->get(DefaultAddressChecker::class)->setRandomDefault($employeeAddress);
        }

        return parent::deleteAction();
    }

    /**
     * @return EmployeeAddressInterface
     */
    protected function createNewEntity(): EmployeeAddressInterface
    {
        $entity = new EmployeeAddress();
        if ($employeeId = $this->get('session')->get('employeeId')) {
            $entity->setEmployee($this->container->get(EmployeeRepository::class)->find($employeeId));
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

        return $this->container->get(EmployeeAddressManipulator::class)->manipulate($builder, $entity);
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
        return $this->container->get(EmployeeRepository::class)->createAddressQueryBuilder($sortField, $sortDirection, $dqlFilter, null !== $this->get('session')->get('employeeId'));
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
        return $this->container->get(EmployeeRepository::class)->createSearchAddressQueryBuilder($searchQuery, $sortField, $sortDirection, $dqlFilter, null !== $this->get('session')->get('employeeId'));
    }
}

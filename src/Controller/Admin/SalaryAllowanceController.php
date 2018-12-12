<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Salary\StateType;
use KejawenLab\Application\SemartHris\Form\Manipulator\SalaryAllowanceManipulator;
use KejawenLab\Application\SemartHris\Repository\SalaryAllowanceRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SalaryAllowanceController extends AdminController
{
    const SALARY_ALLOWANCE_STATE = 'SALARY_ALLOWANCE_STATE';

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
        $session = $this->request->getSession();
        if ($session->has(self::SALARY_ALLOWANCE_STATE)) {
            if (!$state = $this->request->query->get('state')) {
                $this->request->query->set('state', $session->get(self::SALARY_ALLOWANCE_STATE));
            } else {
                $session->set(self::SALARY_ALLOWANCE_STATE, $this->request->query->get('state'));
            }
        } else {
            $session->set(self::SALARY_ALLOWANCE_STATE, $this->request->query->get('state', StateType::STATE_PLUS));
            $this->request->query->set('state', $session->get(self::SALARY_ALLOWANCE_STATE));
        }

        return $this->container->get(SalaryAllowanceRepository::class)->createListQueryBuilder($this->request, $sortDirection, $sortField, $dqlFilter);
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

        return $this->container->get(SalaryAllowanceManipulator::class)->manipulate($builder, $entity);
    }
}

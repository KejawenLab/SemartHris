<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\CompanyPayrollCostInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\ComponentInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollDetailInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PayrollRepository extends Repository implements PayrollRepositoryInterface
{
    /**
     * @var string
     */
    private $detailClass;

    /**
     * @var string
     */
    private $companyCostClass;

    /**
     * @param string $detailClass
     * @param string $companyCostClass
     */
    public function __construct(string $detailClass, string $companyCostClass)
    {
        $this->detailClass = $detailClass;
        $this->companyCostClass = $companyCostClass;
    }

    /**
     * @param EmployeeInterface $employee
     *
     * @return bool
     */
    public function hasPayroll(EmployeeInterface $employee): bool
    {
        $payroll = $this->entityManager->getRepository($this->entityClass)->findOneBy(['employee' => $employee]);
        if ($payroll) {
            return true;
        }

        return false;
    }

    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return PayrollInterface|null
     */
    public function findPayroll(EmployeeInterface $employee, PayrollPeriodInterface $period): ? PayrollInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy([
            'employee' => $employee,
            'period' => $period,
        ]);
    }

    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return PayrollInterface
     */
    public function createPayroll(EmployeeInterface $employee, PayrollPeriodInterface $period): PayrollInterface
    {
        $payroll = $this->findPayroll($employee, $period);
        if (!$payroll) {
            /** @var PayrollInterface $payroll */
            $payroll = new $this->entityClass();
            $payroll->setEmployee($employee);
            $payroll->setPeriod($period);
        }

        return $payroll;
    }

    /**
     * @param PayrollInterface   $payroll
     * @param ComponentInterface $component
     *
     * @return PayrollDetailInterface
     */
    public function createPayrollDetail(PayrollInterface $payroll, ComponentInterface $component): PayrollDetailInterface
    {
        $payrollDetail = $this->findPayrollDetail($payroll, $component);
        if (!$payrollDetail) {
            /** @var PayrollDetailInterface $payrollDetail */
            $payrollDetail = new $this->detailClass();
            $payrollDetail->setPayroll($payroll);
            $payrollDetail->setComponent($component);
        }

        return $payrollDetail;
    }

    /**
     * @param PayrollInterface   $payroll
     * @param ComponentInterface $component
     *
     * @return CompanyPayrollCostInterface
     */
    public function createCompanyCost(PayrollInterface $payroll, ComponentInterface $component): CompanyPayrollCostInterface
    {
        $companyCost = $this->findCompanyCost($payroll, $component);
        if (!$companyCost) {
            /** @var CompanyPayrollCostInterface $companyCost */
            $companyCost = new $this->companyCostClass();
            $companyCost->setPayroll($payroll);
            $companyCost->setComponent($component);
        }

        return $companyCost;
    }

    /**
     * @param PayrollInterface $payroll
     */
    public function store(PayrollInterface $payroll): void
    {
        $this->entityManager->persist($payroll);
    }

    /**
     * @param PayrollDetailInterface $payrollDetail
     */
    public function storeDetail(PayrollDetailInterface $payrollDetail): void
    {
        $companyCost = $this->createCompanyCost($payrollDetail->getPayroll(), $payrollDetail->getComponent());
        $companyCost->setBenefitValue($payrollDetail->getBenefitValue());
        $this->storeCompanyCost($companyCost);

        $this->entityManager->persist($payrollDetail);
    }

    /**
     * @param CompanyPayrollCostInterface $companyCost
     */
    public function storeCompanyCost(CompanyPayrollCostInterface $companyCost): void
    {
        $this->entityManager->persist($companyCost);
    }

    public function update(): void
    {
        $this->entityManager->flush();
    }

    /**
     * @param Request     $request
     * @param string      $sortDirection
     * @param null|string $sortField
     * @param null|string $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder(Request $request, string $sortDirection = 'ASC', ?string $sortField, ?string $dqlFilter): QueryBuilder
    {
        $now = new \DateTime();
        $year = $request->query->get('year', $now->format('Y'));
        $month = $request->query->get('month', $now->format('n'));

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('entity');
        $queryBuilder->from($this->entityClass, 'entity');
        $queryBuilder->innerJoin('entity.employee', 'employee');
        $queryBuilder->innerJoin('entity.period', 'period');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('period.year', $queryBuilder->expr()->literal($year)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('period.month', $queryBuilder->expr()->literal($month)));

        if ($company = $request->query->get('company')) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('employee.company', $queryBuilder->expr()->literal($company)));
        }

        if ($employee = $request->query->get('employeeId')) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('employee.id', $queryBuilder->expr()->literal($employee)));
        }

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy(sprintf('entity.%s', $sortField), $sortDirection);
        }

        return $queryBuilder;
    }

    /**
     * @param PayrollInterface   $payroll
     * @param ComponentInterface $component
     *
     * @return PayrollDetailInterface|null
     */
    private function findPayrollDetail(PayrollInterface $payroll, ComponentInterface $component): ? PayrollDetailInterface
    {
        return $this->entityManager->getRepository($this->detailClass)->findOneBy([
            'payroll' => $payroll,
            'component' => $component,
        ]);
    }

    /**
     * @param PayrollInterface   $payroll
     * @param ComponentInterface $component
     *
     * @return CompanyPayrollCostInterface|null
     */
    private function findCompanyCost(PayrollInterface $payroll, ComponentInterface $component): ? CompanyPayrollCostInterface
    {
        return $this->entityManager->getRepository($this->companyCostClass)->findOneBy([
            'payroll' => $payroll,
            'component' => $component,
        ]);
    }
}

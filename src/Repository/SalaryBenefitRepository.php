<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\BenefitInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\ComponentInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\BenefitRepositoryInterface;
use KejawenLab\Application\SemartHris\Util\UuidUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SalaryBenefitRepository extends Repository implements BenefitRepositoryInterface
{
    /**
     * @var array
     */
    private $excludes;

    /**
     * @param array $excludes
     */
    public function __construct(array $excludes)
    {
        $this->excludes = $excludes;
    }

    /**
     * @param EmployeeInterface $employee
     *
     * @return BenefitInterface[]
     */
    public function findFixedByEmployee(EmployeeInterface $employee): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'b');
        $queryBuilder->select('b');
        $queryBuilder->innerJoin('b.component', 'c');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('c.fixed', $queryBuilder->expr()->literal(true)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('b.employee', $queryBuilder->expr()->literal($employee->getId())));
        $queryBuilder->andWhere($queryBuilder->expr()->notIn('c.code', ':excludes'));
        $queryBuilder->setParameter('excludes', $this->excludes);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param EmployeeInterface  $employee
     * @param ComponentInterface $component
     *
     * @return BenefitInterface|null
     */
    public function findByEmployeeAndComponent(EmployeeInterface $employee, ComponentInterface $component): ? BenefitInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy([
            'employee' => $employee,
            'component' => $component,
        ]);
    }

    public function update(BenefitInterface $benefit): void
    {
        $this->entityManager->persist($benefit);
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
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('entity');
        $queryBuilder->from($this->entityClass, 'entity');

        $employeeId = $request->query->get('employeeId');
        if ($employeeId && UuidUtil::isValid($employeeId)) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('entity.employee', $queryBuilder->expr()->literal($employeeId)));
        }

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy(sprintf('entity.%s', $sortField), $sortDirection);
        }

        return $queryBuilder;
    }
}

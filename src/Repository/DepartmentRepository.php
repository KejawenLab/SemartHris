<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemartHris\Component\Company\Repository\DepartmentRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class DepartmentRepository extends Repository implements DepartmentRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return DepartmentInterface
     */
    public function find(string $id): ? DepartmentInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->find($id);
    }

    /**
     * @param string $companyId
     *
     * @return DepartmentInterface[]
     */
    public function findByCompany(string $companyId): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o');
        $queryBuilder->from($this->entityClass, 'o');
        $queryBuilder->addSelect('d.id');
        $queryBuilder->addSelect('d.code');
        $queryBuilder->addSelect('d.name');
        $queryBuilder->leftJoin('o.department', 'd');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.company', $queryBuilder->expr()->literal($companyId)));

        return $queryBuilder->getQuery()->getResult();
    }
}

<?php

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\EntityRepository;
use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemartHris\Component\Company\Repository\DepartmentRepositoryInterface;
use KejawenLab\Application\SemartHris\Entity\CompanyDepartment;

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
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(CompanyDepartment::class);

        $queryBuilder = $repository->createQueryBuilder('cd');
        $queryBuilder->addSelect('d.id');
        $queryBuilder->addSelect('d.code');
        $queryBuilder->addSelect('d.name');
        $queryBuilder->leftJoin('cd.department', 'd');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('cd.company', $queryBuilder->expr()->literal($companyId)));

        return $queryBuilder->getQuery()->getResult();
    }
}

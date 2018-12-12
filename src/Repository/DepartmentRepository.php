<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemartHris\Component\Company\Repository\DepartmentRepositoryInterface;
use KejawenLab\Application\SemartHris\Entity\CompanyDepartment;
use KejawenLab\Application\SemartHris\Util\UuidUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class DepartmentRepository extends Repository implements DepartmentRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return DepartmentInterface
     */
    public function find(?string $id): ? DepartmentInterface
    {
        return $this->doFind($id);
    }

    /**
     * @param string $companyId
     *
     * @return DepartmentInterface[]
     */
    public function findByCompany(string $companyId): array
    {
        if (!$companyId || !UuidUtil::isValid($companyId)) {
            return [];
        }

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o');
        $queryBuilder->from(CompanyDepartment::class, 'o');
        $queryBuilder->addSelect('d.id');
        $queryBuilder->addSelect('d.code');
        $queryBuilder->addSelect('d.name');
        $queryBuilder->leftJoin('o.department', 'd');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.company', $queryBuilder->expr()->literal($companyId)));

        return $queryBuilder->getQuery()->getResult();
    }
}

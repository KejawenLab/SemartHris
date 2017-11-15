<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Job\Model\JobTitleInterface;
use KejawenLab\Application\SemartHris\Component\Job\Repository\JobTitleRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class JobTitleRepository extends Repository implements JobTitleRepositoryInterface
{
    /**
     * @param string $jobLevelId
     *
     * @return JobTitleInterface[]
     */
    public function findByJobLevel(string $jobLevelId): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o');
        $queryBuilder->from($this->entityClass, 'o');
        $queryBuilder->addSelect('o.id');
        $queryBuilder->addSelect('o.code');
        $queryBuilder->addSelect('o.name');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.jobLevel', $queryBuilder->expr()->literal($jobLevelId)));

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $id
     *
     * @return JobTitleInterface
     */
    public function find(?string $id): ? JobTitleInterface
    {
        return $this->doFind($id);
    }
}

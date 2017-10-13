<?php

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\EntityRepository;
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
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository($this->entityClass);

        $queryBuilder = $repository->createQueryBuilder('jt');
        $queryBuilder->addSelect('jt.id');
        $queryBuilder->addSelect('jt.code');
        $queryBuilder->addSelect('jt.name');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('jt.jobLevel', $queryBuilder->expr()->literal($jobLevelId)));

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $id
     *
     * @return JobTitleInterface
     */
    public function find(string $id): ? JobTitleInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->find($id);
    }
}

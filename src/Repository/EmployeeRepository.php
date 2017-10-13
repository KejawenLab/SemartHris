<?php

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\EntityRepository;
use KejawenLab\Application\SemartHris\Component\Employee\Repository\SupervisorRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;
use KejawenLab\Application\SemartHris\Entity\JobLevel;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class EmployeeRepository extends Repository implements SupervisorRepositoryInterface
{
    /**
     * @param string $jobLevelId
     *
     * @return array
     */
    public function findSupervisorByJobLevel(string $jobLevelId): array
    {
        $jobLevel = $this->entityManager->getRepository(JobLevel::class)->find($jobLevelId);
        /** @var JobLevelInterface $parentLevel */
        $parentLevel = $jobLevel->getParent();
        if (!($jobLevel && $parentLevel)) {
            return [];
        }

        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository($this->entityClass);

        $queryBuilder = $repository->createQueryBuilder('e');
        $queryBuilder->addSelect('e.id');
        $queryBuilder->addSelect('e.code');
        $queryBuilder->addSelect('e.fullName');
        $queryBuilder->orWhere($queryBuilder->expr()->eq('e.jobLevel', $queryBuilder->expr()->literal($parentLevel->getId())));
        $queryBuilder->orWhere($queryBuilder->expr()->eq('e.jobLevel', $queryBuilder->expr()->literal($jobLevel->getId())));

        return $queryBuilder->getQuery()->getResult();
    }
}

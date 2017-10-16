<?php

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Repository\EmployeeRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Repository\SupervisorRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;
use KejawenLab\Application\SemartHris\Component\Security\Model\UserInterface;
use KejawenLab\Application\SemartHris\Component\Security\Repository\UserRepositoryInterface;
use KejawenLab\Application\SemartHris\Entity\JobLevel;
use KejawenLab\Library\PetrukUsername\Repository\UsernameInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class EmployeeRepository extends Repository implements EmployeeRepositoryInterface, SupervisorRepositoryInterface, UserRepositoryInterface
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

    /**
     * @param string $id
     *
     * @return EmployeeInterface
     */
    public function find(string $id): ? EmployeeInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->find($id);
    }

    /**
     * @param string $username
     *
     * @return bool
     */
    public function isExist(string $username): bool
    {
        if ($this->findByUsername($username)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $characters
     *
     * @return int
     */
    public function countUsage(string $characters): int
    {
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository($this->entityClass);
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $repository->createQueryBuilder('o');
        $queryBuilder->select('COUNT(1)');
        $queryBuilder->andWhere($queryBuilder->expr()->like('o.username', $queryBuilder->expr()->literal(sprintf('%%%s%%', $characters))));

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param UsernameInterface $username
     */
    public function save(UsernameInterface $username): void
    {
        throw new \RuntimeException('This method is not implemented');
    }

    /**
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function findByUsername(string $username): ? UserInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy(['username' => $username]);
    }
}

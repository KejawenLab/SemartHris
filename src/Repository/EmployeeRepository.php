<?php

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Address\Repository\AddressRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Contract\Repository\ContractableRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeAddressInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Repository\EmployeeRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;
use KejawenLab\Application\SemartHris\Component\User\Model\UserInterface;
use KejawenLab\Application\SemartHris\Component\User\Repository\UserRepositoryInterface;
use KejawenLab\Application\SemartHris\Entity\EmployeeAddress;
use KejawenLab\Application\SemartHris\Entity\JobLevel;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use KejawenLab\Library\PetrukUsername\Repository\UsernameInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class EmployeeRepository extends Repository implements EmployeeRepositoryInterface, UserRepositoryInterface, AddressRepositoryInterface, ContractableRepositoryInterface
{
    use AddressRepositoryTrait;
    use ContractableRepositoryTrait;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

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

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'e');
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
     * @return EmployeeInterface[]
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository($this->entityClass)->findAll();
    }

    /**
     * @param string $code
     *
     * @return EmployeeInterface|null
     */
    public function findByCode(string $code): ? EmployeeInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy(['code' => StringUtil::uppercase($code)]);
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
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'o');
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
        return $this->entityManager->getRepository($this->entityClass)->findOneBy([
            'username' => $username,
            'resignDate' => null,
        ]);
    }

    /**
     * @param string $employeeAddressId
     *
     * @return null|EmployeeAddressInterface
     */
    public function findEmployeeAddress(string $employeeAddressId): ? EmployeeAddressInterface
    {
        return $this->entityManager->getRepository($this->getEntityClass())->find($employeeAddressId);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'o');
        $queryBuilder->select('COUNT(1)');

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param string|null $sortField
     * @param string|null $sortDirection
     * @param string|null $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createEmployeeQueryBuilder(string $sortField = null, string $sortDirection = null, string $dqlFilter = null)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('entity');
        $queryBuilder->from($this->entityClass, 'entity');

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy('entity.'.$sortField, $sortDirection ?: 'DESC');
        }

        return $queryBuilder;
    }

    /**
     * @param string      $searchQuery
     * @param string|null $sortField
     * @param string|null $sortDirection
     * @param string|null $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createSearchEmployeeQueryBuilder(string $searchQuery, string $sortField = null, string $sortDirection = null, string $dqlFilter = null)
    {
        $queryBuilder = $this->createEmployeeQueryBuilder($sortField, $sortDirection, $dqlFilter);
        $queryBuilder->orWhere('LOWER(entity.code) LIKE :query');
        $queryBuilder->orWhere('LOWER(entity.fullName) LIKE :query');
        $queryBuilder->setParameter('query', sprintf('%%%s%%', StringUtil::lowercase($searchQuery)));

        return $queryBuilder;
    }

    /**
     * @param null $sortField
     * @param null $sortDirection
     * @param null $dqlFilter
     * @param bool $useEmployeeFilter
     *
     * @return QueryBuilder
     */
    public function createEmployeeAddressQueryBuilder($sortField = null, $sortDirection = null, $dqlFilter = null, $useEmployeeFilter = true)
    {
        return $this->buildEmployeeFilterableSearch($this->getEntityClass(), $sortField, $sortDirection, $dqlFilter, $useEmployeeFilter);
    }

    /**
     * @param string $searchQuery
     * @param null   $sortField
     * @param null   $sortDirection
     * @param null   $dqlFilter
     * @param bool   $useEmployeeFilter
     *
     * @return QueryBuilder
     */
    public function createSearchEmployeeAddressQueryBuilder($searchQuery, $sortField = null, $sortDirection = null, $dqlFilter = null, $useEmployeeFilter = true)
    {
        $queryBuilder = $this->createEmployeeAddressQueryBuilder($sortField, $sortDirection, $dqlFilter, $useEmployeeFilter);

        return $this->createSearchAddressQueryBuilder($queryBuilder, $searchQuery);
    }

    /**
     * @param string $entityClass
     * @param null   $sortField
     * @param null   $sortDirection
     * @param null   $dqlFilter
     * @param bool   $useEmployeeFilter
     *
     * @return QueryBuilder
     */
    private function buildEmployeeFilterableSearch(string $entityClass, $sortField = null, $sortDirection = null, $dqlFilter = null, $useEmployeeFilter = true)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('entity');
        $queryBuilder->from($entityClass, 'entity');

        if ($useEmployeeFilter && $employeeId = $this->session->get('employeeId')) {
            $queryBuilder->orWhere('entity.employee = :query')->setParameter('query', $this->find($employeeId));
        }

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy('entity.'.$sortField, $sortDirection ?: 'DESC');
        }

        return $queryBuilder;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return EmployeeAddress::class;
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemartHris\Component\Address\Repository\AddressRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Component\Contract\Repository\ContractableRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeAddressInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Repository\EmployeeRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;
use KejawenLab\Application\SemartHris\Component\User\Model\UserInterface;
use KejawenLab\Application\SemartHris\Component\User\Repository\UserRepositoryInterface;
use KejawenLab\Application\SemartHris\Entity\Company;
use KejawenLab\Application\SemartHris\Entity\EmployeeAddress;
use KejawenLab\Application\SemartHris\Entity\JobLevel;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use KejawenLab\Application\SemartHris\Util\UuidUtil;
use KejawenLab\Library\PetrukUsername\Repository\UsernameInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
        if (!$jobLevelId || !UuidUtil::isValid($jobLevelId)) {
            return [];
        }

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
     * @param string $companyId
     *
     * @return array
     */
    public function findByCompany(string $companyId): array
    {
        if (!$companyId || !UuidUtil::isValid($companyId)) {
            return [];
        }

        $company = $this->entityManager->getRepository(Company::class)->find($companyId);
        if (!$company) {
            return [];
        }

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'e');
        $queryBuilder->select('e');
        $queryBuilder->orWhere($queryBuilder->expr()->eq('e.company', $queryBuilder->expr()->literal($company->getId())));

        /** @var CompanyInterface $parentCompany */
        $parentCompany = $company->getParent();
        if ($parentCompany) {
            $queryBuilder->orWhere($queryBuilder->expr()->eq('e.company', $queryBuilder->expr()->literal($parentCompany->getId())));
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param AddressInterface $address
     */
    public function unsetDefaultExcept(AddressInterface $address): void
    {/** @var EmployeeAddressInterface $address */
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->getAddressClass(), 'o');
        $queryBuilder->update();
        $queryBuilder->set('o.defaultAddress', $queryBuilder->expr()->literal(false));

        $employee = $address->getEmployee();
        if ($employee && UuidUtil::isValid($employee->getId())) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('o.employee', $queryBuilder->expr()->literal($employee->getId())));
        }

        if (UuidUtil::isValid($address->getId())) {
            $queryBuilder->andWhere($queryBuilder->expr()->neq('o.id', $queryBuilder->expr()->literal($address->getId())));
        }

        $queryBuilder->getQuery()->execute();
    }

    /**
     * @param string $id
     *
     * @return EmployeeInterface
     */
    public function find(?string $id): ? EmployeeInterface
    {
        return $this->doFind($id);
    }

    /**
     * @param array $ids
     *
     * @return EmployeeInterface[]
     */
    public function finds(array $ids = []): array
    {
        if (empty($ids)) {
            return [];
        }

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'o');
        $queryBuilder->select('o');
        $queryBuilder->andWhere($queryBuilder->expr()->in('o.id', ':ids'));
        $queryBuilder->setParameter('ids', $ids);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return EmployeeInterface[]
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository($this->entityClass)->findAll();
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function search(Request $request): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'e');
        $queryBuilder->addSelect('e.id');
        $queryBuilder->addSelect('e.code');
        $queryBuilder->addSelect('e.fullName');
        $queryBuilder->orWhere($queryBuilder->expr()->like('e.code', ':search'));
        $queryBuilder->orWhere($queryBuilder->expr()->like('e.fullName', ':search'));
        $queryBuilder->setParameter('search', sprintf('%%%s%%', StringUtil::uppercase($request->query->get('search'))));
        $queryBuilder->andWhere($queryBuilder->expr()->isNull('e.resignDate'));
        $queryBuilder->orWhere($queryBuilder->expr()->gte('e.resignDate', $queryBuilder->expr()->literal(date('Y-m-d 23:59:59'))));

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $code
     *
     * @return EmployeeInterface|null
     */
    public function findByCode(string $code): ? EmployeeInterface
    {
        if (!$code) {
            return null;
        }

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
        if (!$username) {
            return null;
        }

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('e');
        $queryBuilder->from($this->entityClass, 'e');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('e.username', $queryBuilder->expr()->literal($username)));
        $queryBuilder->andWhere($queryBuilder->expr()->isNull('e.resignDate'));
        $queryBuilder->orWhere($queryBuilder->expr()->gte('e.resignDate', $queryBuilder->expr()->literal(date('Y-m-d 00:00:00'))));

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $employeeAddressId
     *
     * @return null|EmployeeAddressInterface
     */
    public function findAddress(string $employeeAddressId): ? EmployeeAddressInterface
    {
        if (!$employeeAddressId || !UuidUtil::isValid($employeeAddressId)) {
            return null;
        }

        return $this->entityManager->getRepository($this->getAddressClass())->find($employeeAddressId);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'o');
        $queryBuilder->select('COUNT(1)');

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param null|string $sortField
     * @param string      $sortDirection
     * @param null|string $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(?string $sortField, string $sortDirection = 'ASC', ?string $dqlFilter)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('entity');
        $queryBuilder->from($this->entityClass, 'entity');

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy(sprintf('entity.%s', $sortField), $sortDirection);
        }

        return $queryBuilder;
    }

    /**
     * @param string      $searchQuery
     * @param null|string $sortField
     * @param string      $sortDirection
     * @param null|string $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createSearchQueryBuilder(string $searchQuery, ?string $sortField, string $sortDirection = 'ASC', ?string $dqlFilter)
    {
        $queryBuilder = $this->createQueryBuilder($sortField, $sortDirection, $dqlFilter);
        $queryBuilder->orWhere('LOWER(entity.code) LIKE :query');
        $queryBuilder->orWhere('LOWER(entity.fullName) LIKE :query');
        $queryBuilder->setParameter('query', sprintf('%%%s%%', StringUtil::lowercase($searchQuery)));

        return $queryBuilder;
    }

    /**
     * @param null|string $sortField
     * @param string      $sortDirection
     * @param null|string $dqlFilter
     * @param bool        $useEmployeeFilter
     *
     * @return QueryBuilder
     */
    public function createAddressQueryBuilder(?string $sortField, string $sortDirection = 'ASc', ?string $dqlFilter, bool $useEmployeeFilter = true)
    {
        return $this->buildFilterableSearch($this->getAddressClass(), $sortField, $sortDirection, $dqlFilter, $useEmployeeFilter);
    }

    /**
     * @param string      $searchQuery
     * @param null|string $sortField
     * @param string      $sortDirection
     * @param null|string $dqlFilter
     * @param bool        $useEmployeeFilter
     *
     * @return QueryBuilder
     */
    public function createSearchAddressQueryBuilder(string $searchQuery, ?string $sortField, string $sortDirection = 'ASC', ?string $dqlFilter, bool $useEmployeeFilter = true)
    {
        $queryBuilder = $this->createAddressQueryBuilder($sortField, $sortDirection, $dqlFilter, $useEmployeeFilter);

        return $this->buildSearchAddressQueryBuilder($queryBuilder, $searchQuery);
    }

    /**
     * @param string      $entityClass
     * @param null|string $sortField
     * @param string      $sortDirection
     * @param null|string $dqlFilter
     * @param bool        $useEmployeeFilter
     *
     * @return QueryBuilder
     */
    private function buildFilterableSearch(string $entityClass, ?string $sortField, string $sortDirection = 'ASC', ?string $dqlFilter, bool $useEmployeeFilter = true)
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
            $queryBuilder->orderBy(sprintf('entity.%s', $sortField), $sortDirection);
        }

        return $queryBuilder;
    }

    /**
     * @return string
     */
    public function getAddressClass(): string
    {
        return EmployeeAddress::class;
    }
}

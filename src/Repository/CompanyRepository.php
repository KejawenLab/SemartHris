<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemartHris\Component\Address\Repository\AddressRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyAddressInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Component\Company\Repository\CompanyRepositoryInterface;
use KejawenLab\Application\SemartHris\Entity\Company;
use KejawenLab\Application\SemartHris\Entity\CompanyAddress;
use KejawenLab\Application\SemartHris\Entity\CompanyDepartment;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use KejawenLab\Application\SemartHris\Util\UuidUtil;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyRepository extends Repository implements CompanyRepositoryInterface, AddressRepositoryInterface
{
    use AddressRepositoryTrait;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param EntityManagerInterface $entityManager
     * @param SessionInterface       $session
     */
    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->initialize($entityManager, Company::class);
    }

    /**
     * @param string $id
     *
     * @return null|CompanyInterface
     */
    public function find(?string $id): ? CompanyInterface
    {
        return $this->doFind($id);
    }

    /**
     * @return CompanyInterface[]
     */
    public function findAll(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('entity.id, entity. code, entity.name');
        $queryBuilder->from($this->entityClass, 'entity');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $companyAddressId
     *
     * @return null|CompanyAddressInterface
     */
    public function findAddress(string $companyAddressId): ? CompanyAddressInterface
    {
        if (!$companyAddressId || !UuidUtil::isValid($companyAddressId)) {
            return null;
        }

        return $this->entityManager->getRepository($this->getAddressClass())->find($companyAddressId);
    }

    /**
     * @param AddressInterface $address
     */
    public function unsetDefaultExcept(AddressInterface $address): void
    {/** @var CompanyAddressInterface $address */
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->getAddressClass(), 'o');
        $queryBuilder->update();
        $queryBuilder->set('o.defaultAddress', $queryBuilder->expr()->literal(false));

        $company = $address->getCompany();
        if ($company && UuidUtil::isValid($company->getId())) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('o.company', $queryBuilder->expr()->literal($company->getId())));
        }

        if (UuidUtil::isValid($address->getId())) {
            $queryBuilder->andWhere($queryBuilder->expr()->neq('o.id', $queryBuilder->expr()->literal($address->getId())));
        }

        $queryBuilder->getQuery()->execute();

        $address->setDefaultAddress(true);
        $this->entityManager->persist($address);
        $this->entityManager->flush();
    }

    /**
     * @param null|string $sortField
     * @param null|string $sortDirection
     * @param null|string $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createDepartmentQueryBuilder(?string $sortField, ?string $sortDirection, ?string $dqlFilter)
    {
        return $this->buildSearch(CompanyDepartment::class, $sortField, $sortDirection, $dqlFilter);
    }

    /**
     * @param $searchQuery
     * @param null|string $sortField
     * @param string      $sortDirection
     * @param null|string $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createSearchDepartmentQueryBuilder($searchQuery, ?string $sortField, string $sortDirection = 'ASC', ?string $dqlFilter)
    {
        $queryBuilder = $this->createDepartmentQueryBuilder($sortField, $sortDirection, $dqlFilter);
        $queryBuilder->leftJoin('entity.company', 'company');
        $queryBuilder->orWhere('LOWER(company.code) LIKE :query');
        $queryBuilder->orWhere('LOWER(company.name) LIKE :query');
        $queryBuilder->leftJoin('entity.department', 'department');
        $queryBuilder->orWhere('LOWER(department.code) LIKE :query');
        $queryBuilder->orWhere('LOWER(department.name) LIKE :query');

        $queryBuilder->setParameter('query', sprintf('%%%s%%', StringUtil::lowercase($searchQuery)));

        return $queryBuilder;
    }

    /**
     * @param null|string $sortField
     * @param string      $sortDirection
     * @param null|string $dqlFilter
     * @param bool        $useCompanyFilter
     *
     * @return QueryBuilder
     */
    public function createAddressQueryBuilder(?string $sortField, string $sortDirection = 'ASC', ?string $dqlFilter, bool $useCompanyFilter = true)
    {
        return $this->buildSearch($this->getAddressClass(), $sortField, $sortDirection, $dqlFilter, $useCompanyFilter);
    }

    /**
     * @param string      $searchQuery
     * @param null|string $sortField
     * @param string      $sortDirection
     * @param null|string $dqlFilter
     * @param bool        $useCompanyFilter
     *
     * @return QueryBuilder
     */
    public function createSearchAddressQueryBuilder(string $searchQuery, ?string $sortField, string $sortDirection = 'ASC', ?string $dqlFilter, bool $useCompanyFilter = true)
    {
        $queryBuilder = $this->createAddressQueryBuilder($sortField, $sortDirection, $dqlFilter, $useCompanyFilter);

        return $this->buildSearchAddressQueryBuilder($queryBuilder, $searchQuery);
    }

    /**
     * @param string      $entityClass
     * @param null|string $sortField
     * @param string      $sortDirection
     * @param null|string $dqlFilter
     * @param bool        $useCompanyFilter
     *
     * @return QueryBuilder
     */
    private function buildSearch(string $entityClass, ?string $sortField, string $sortDirection = 'ASC', ?string $dqlFilter, bool $useCompanyFilter = true)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('entity');
        $queryBuilder->from($entityClass, 'entity');

        if ($useCompanyFilter && $companyId = $this->session->get('companyId')) {
            $queryBuilder->orWhere('entity.company = :query')->setParameter('query', $this->find($companyId));
        }

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy('entity.'.$sortField, $sortDirection ?? 'DESC');
        }

        return $queryBuilder;
    }

    /**
     * @return string
     */
    public function getAddressClass(): string
    {
        return CompanyAddress::class;
    }
}

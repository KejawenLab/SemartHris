<?php

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemartHris\Component\Address\Repository\AddressRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyAddressInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Component\Company\Repository\CompanyRepositoryInterface;
use KejawenLab\Application\SemartHris\Entity\Company;
use KejawenLab\Application\SemartHris\Entity\CompanyAddress;
use KejawenLab\Application\SemartHris\Entity\CompanyDepartment;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class CompanyRepository implements CompanyRepositoryInterface, AddressRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param EntityManagerInterface $entityManager
     * @param SessionInterface       $session
     */
    public function initialize(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    /**
     * @param string $id
     *
     * @return null|CompanyInterface
     */
    public function find(string $id): ? CompanyInterface
    {
        return $this->entityManager->getRepository(Company::class)->find($id);
    }

    /**
     * @param string $companyAddressId
     *
     * @return null|CompanyAddressInterface
     */
    public function findCompanyAddress(string $companyAddressId): ? CompanyAddressInterface
    {
        return $this->entityManager->getRepository($this->getEntityClass())->find($companyAddressId);
    }

    /**
     * @param null $sortField
     * @param null $sortDirection
     * @param null $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createCompanyDepartmentQueryBuilder($sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        return $this->buildSearch(CompanyDepartment::class, $sortField, $sortDirection, $dqlFilter);
    }

    /**
     * @param null $sortField
     * @param null $sortDirection
     * @param null $dqlFilter
     * @param bool $useCompanyFilter
     *
     * @return QueryBuilder
     */
    public function createCompanyAddressQueryBuilder($sortField = null, $sortDirection = null, $dqlFilter = null, $useCompanyFilter = true)
    {
        return $this->buildSearch(CompanyAddress::class, $sortField, $sortDirection, $dqlFilter, $useCompanyFilter);
    }

    /**
     * @param string $searchQuery
     * @param null   $sortField
     * @param null   $sortDirection
     * @param null   $dqlFilter
     * @param bool   $useCompanyFilter
     *
     * @return QueryBuilder
     */
    public function createSearchCompanyAddressQueryBuilder($searchQuery, $sortField = null, $sortDirection = null, $dqlFilter = null, $useCompanyFilter = true)
    {
        $queryBuilder = $this->createCompanyAddressQueryBuilder($sortField, $sortDirection, $dqlFilter, $useCompanyFilter);

        $queryBuilder->leftJoin('entity.region', 'region');
        $queryBuilder->orWhere('region.code LIKE :query');
        $queryBuilder->orWhere('region.name LIKE :query');
        $queryBuilder->leftJoin('entity.city', 'city');
        $queryBuilder->orWhere('city.code LIKE :query');
        $queryBuilder->orWhere('city.name LIKE :query');
        $queryBuilder->orWhere('entity.address LIKE :query');
        $queryBuilder->orWhere('entity.postalCode LIKE :query');
        $queryBuilder->orWhere('entity.phoneNumber LIKE :query');
        $queryBuilder->orWhere('entity.faxNumber LIKE :query');

        $queryBuilder->setParameter('query', sprintf('%%%s%%', $searchQuery));

        return $queryBuilder;
    }

    /**
     * @param string $entityClass
     * @param null   $sortField
     * @param null   $sortDirection
     * @param null   $dqlFilter
     * @param bool   $useCompanyFilter
     *
     * @return QueryBuilder
     */
    private function buildSearch(string $entityClass, $sortField = null, $sortDirection = null, $dqlFilter = null, $useCompanyFilter = true)
    {
        /* @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('entity')
            ->from($entityClass, 'entity')
        ;

        if ($useCompanyFilter && $companyId = $this->session->get('companyId')) {
            $queryBuilder->orWhere('entity.company = :query')->setParameter('query', $this->find($companyId));
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
     * @param AddressInterface $address
     */
    public function unsetDefaultExcept(AddressInterface $address): void
    {
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository($this->getEntityClass());

        $queryBuilder = $repository->createQueryBuilder('o');
        $queryBuilder->update();
        $queryBuilder->set('o.defaultAddress', $queryBuilder->expr()->literal(false));
        $queryBuilder->andWhere($queryBuilder->expr()->neq('o.id', $queryBuilder->expr()->literal($address->getId())));

        $queryBuilder->getQuery()->execute();
    }

    public function setRandomDefault(): void
    {
        /** @var AddressInterface $other */
        $other = $this->entityManager->getRepository($this->getEntityClass())->findOneBy(['defaultAddress' => false]);
        if (!$other) {
            throw new NotFoundHttpException();
        }

        $other->setDefaultAddress(true);
        $this->entityManager->persist($other);
        $this->entityManager->flush();
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return CompanyAddress::class;
    }
}

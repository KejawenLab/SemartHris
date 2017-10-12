<?php

namespace KejawenLab\Application\SemarHris\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemarHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemarHris\Component\Address\Repository\AddressRepositoryInterface;
use KejawenLab\Application\SemarHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemarHris\Entity\Company;
use KejawenLab\Application\SemarHris\Entity\CompanyAddress;
use KejawenLab\Application\SemarHris\Entity\CompanyDepartment;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class CompanyRepository implements AddressRepositoryInterface
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
     * @param null $sortField
     * @param null $sortDirection
     * @param null $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createCompanyDepartmentQueryBuilder($sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        return $this->buildSearch($sortField, $sortDirection, $dqlFilter);
    }

    /**
     * @param null $sortField
     * @param null $sortDirection
     * @param null $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createCompanyAddressQueryBuilder($sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        return $this->buildSearch($sortField, $sortDirection, $dqlFilter);
    }

    /**
     * @param null $sortField
     * @param null $sortDirection
     * @param null $dqlFilter
     *
     * @return QueryBuilder
     */
    private function buildSearch($sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        /* @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('entity')
            ->from(CompanyDepartment::class, 'entity')
            ->andWhere('LOWER(entity.company) = :query')
            ->setParameter('query', $this->find($this->session->get('companyId')))
        ;

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

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return CompanyAddress::class;
    }
}

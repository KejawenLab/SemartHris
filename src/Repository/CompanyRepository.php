<?php

namespace Persona\Hris\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Persona\Hris\Component\Company\Model\CompanyInterface;
use Persona\Hris\Entity\Company;
use Persona\Hris\Entity\CompanyAddress;
use Persona\Hris\Entity\CompanyDepartment;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
class CompanyRepository
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
     * @param ManagerRegistry $managerRegistry
     * @param $searchQuery
     * @param array $searchableFields
     * @param null  $sortField
     * @param null  $sortDirection
     * @param null  $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createCompanyDepartmentQueryBuilder(ManagerRegistry $managerRegistry, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $managerRegistry->getManagerForClass(CompanyDepartment::class);

        return $this->buildSearch($entityManager, $sortField, $sortDirection, $dqlFilter);
    }

    /**
     * @param ManagerRegistry $managerRegistry
     * @param $searchQuery
     * @param array $searchableFields
     * @param null  $sortField
     * @param null  $sortDirection
     * @param null  $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createCompanyAddressQueryBuilder(ManagerRegistry $managerRegistry, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $managerRegistry->getManagerForClass(CompanyAddress::class);

        return $this->buildSearch($entityManager, $sortField, $sortDirection, $dqlFilter);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param null                   $sortField
     * @param null                   $sortDirection
     * @param null                   $dqlFilter
     *
     * @return QueryBuilder
     */
    private function buildSearch(EntityManagerInterface $entityManager, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        /* @var QueryBuilder $queryBuilder */
        $queryBuilder = $entityManager->createQueryBuilder()
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
}

<?php

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Address\Model\Addressable;
use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
trait AddressRepositoryTrait
{
    /**
     * @param AddressInterface $address
     */
    public function unsetDefaultExcept(AddressInterface $address): void
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o');
        $queryBuilder->from($this->getEntityClass(), 'o');
        $queryBuilder->update();
        $queryBuilder->set('o.defaultAddress', $queryBuilder->expr()->literal(false));
        $queryBuilder->andWhere($queryBuilder->expr()->neq('o.id', $queryBuilder->expr()->literal($address->getId())));

        $queryBuilder->getQuery()->execute();
    }

    public function setRandomDefault(): AddressInterface
    {
        /** @var AddressInterface $other */
        $other = $this->entityManager->getRepository($this->getEntityClass())->findOneBy(['defaultAddress' => false]);
        if (!$other) {
            throw new EntityNotFoundException();
        }

        $other->setDefaultAddress(true);
        $this->entityManager->persist($other);
        $this->entityManager->flush();

        return $other;
    }

    /**
     * @param Addressable $address
     */
    public function apply(Addressable $address): void
    {
        $this->entityManager->persist($address);
        $this->entityManager->flush();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $queryString
     *
     * @return QueryBuilder
     */
    protected function createSearchAddressQueryBuilder(QueryBuilder $queryBuilder, string $queryString): QueryBuilder
    {
        $queryBuilder->leftJoin('entity.region', 'region');
        $queryBuilder->orWhere('LOWER(region.code) LIKE :query');
        $queryBuilder->orWhere('LOWER(region.name) LIKE :query');
        $queryBuilder->leftJoin('entity.city', 'city');
        $queryBuilder->orWhere('LOWER(city.code) LIKE :query');
        $queryBuilder->orWhere('LOWER(city.name) LIKE :query');
        $queryBuilder->orWhere('LOWER(entity.address) LIKE :query');
        $queryBuilder->orWhere('LOWER(entity.postalCode) LIKE :query');
        $queryBuilder->orWhere('LOWER(entity.phoneNumber) LIKE :query');
        $queryBuilder->orWhere('LOWER(entity.faxNumber) LIKE :query');

        $queryBuilder->setParameter('query', sprintf('%%%s%%', StringUtil::lowercase($queryString)));

        return $queryBuilder;
    }
}

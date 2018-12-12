<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Address\Model\Addressable;
use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
trait AddressRepositoryTrait
{
    public function setRandomDefault(): AddressInterface
    {
        /** @var AddressInterface $other */
        $other = $this->entityManager->getRepository($this->getAddressClass())->findOneBy(['defaultAddress' => false]);
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
    protected function buildSearchAddressQueryBuilder(QueryBuilder $queryBuilder, string $queryString): QueryBuilder
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

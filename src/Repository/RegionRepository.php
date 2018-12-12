<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Address\Model\RegionInterface;
use KejawenLab\Application\SemartHris\Component\Address\Repository\RegionRepositoryInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RegionRepository extends Repository implements RegionRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return null|RegionInterface
     */
    public function find(?string $id): ? RegionInterface
    {
        return $this->doFind($id);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function search(Request $request): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'r');
        $queryBuilder->addSelect('r.id');
        $queryBuilder->addSelect('r.code');
        $queryBuilder->addSelect('r.name');
        $queryBuilder->orWhere($queryBuilder->expr()->like('r.code', ':search'));
        $queryBuilder->orWhere($queryBuilder->expr()->like('r.name', ':search'));
        $queryBuilder->setParameter('search', sprintf('%%%s%%', StringUtil::uppercase($request->query->get('search'))));

        return $queryBuilder->getQuery()->getResult();
    }
}

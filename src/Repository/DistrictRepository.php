<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use KejawenLab\Semart\Skeleton\Component\Contract\Address\DistrictInterface;
use KejawenLab\Semart\Skeleton\Component\Contract\Address\DistrictRepositoryInterface;
use KejawenLab\Semart\Skeleton\Entity\District;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class DistrictRepository extends Repository implements DistrictRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, District::class);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?object
    {
        $key = md5(sprintf('%s:%s:%s:%s', __CLASS__, __METHOD__, serialize($criteria), serialize($orderBy)));

        return $this->doFindOneBy($key, $criteria, $orderBy);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        $key = md5(sprintf('%s:%s:%s:%s:%s:%s', __CLASS__, __METHOD__, serialize($criteria), serialize($orderBy), $limit, $offset));

        return $this->doFindBy($key, $criteria, $orderBy, $limit, $offset);
    }

    public function findAll(): array
    {
        return $this->proxy->findAll();
    }

    public function commit(DistrictInterface $district): void
    {
        $this->manager->persist($district);
    }

    public function flush(): void
    {
        $this->manager->flush();
    }
}

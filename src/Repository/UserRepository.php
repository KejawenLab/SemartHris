<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Repository;

use KejawenLab\Semart\Skeleton\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UserRepository extends Repository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
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
}

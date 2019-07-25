<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use KejawenLab\Semart\Skeleton\Entity\JobTitle;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class JobTitleRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobTitle::class);
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

    /**
     * @param int $level
     *
     * @return JobTitle[]
     */
    public function findSupervisors(int $level): array
    {
        $key = md5(sprintf('%s:%s:%s', __CLASS__, __METHOD__, $level));

        $supervisors = $this->getItem($key);
        if (!$supervisors) {
            $queryBuilder = $this->proxy->createQueryBuilder('o');
            $queryBuilder->andWhere($queryBuilder->expr()->lte('o.level', $queryBuilder->expr()->literal($level)));

            $supervisors = $queryBuilder->getQuery()->getResult();

            $this->cache($key, $supervisors);
        }

        return $supervisors;
    }
}

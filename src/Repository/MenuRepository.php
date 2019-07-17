<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use KejawenLab\Semart\Skeleton\Entity\Menu;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return object|null
     */
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

    public function findByCode(string $code): ?Menu
    {
        /** @var Menu|null $menu */
        $menu = $this->findOneBy(['code' => $code]);

        return $menu;
    }

    public function commit(Menu $menu): void
    {
        $this->_em->persist($menu);
        $this->_em->flush();
    }
}

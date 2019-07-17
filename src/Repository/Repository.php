<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Persistence\ManagerRegistry;
use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Contract\Repository\CacheableRepositoryInterface;
use Ramsey\Uuid\Uuid;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class Repository extends ServiceEntityRepository implements CacheableRepositoryInterface
{
    private $cache;

    private $cacheable;

    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);

        $this->cache = new ArrayCache();
        $this->cacheable = false;
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?object
    {
        if (!Uuid::isValid($id)) {
            return null;
        }

        if (!$this->isCacheable()) {
            return parent::find($id);
        }

        $entity = $this->getItem($id);
        if (!$entity) {
            $entity = parent::find($id);

            $this->cache($id, $entity);
        }

        return $entity;
    }

    public function findUniqueBy(array $criteria): array
    {
        $this->_em->getFilters()->disable(sprintf('%s_softdeletable', Application::APP_UNIQUE_NAME));

        return $this->findBy(array_merge($criteria));
    }

    public function isCacheable(): bool
    {
        return $this->cacheable;
    }

    public function setCacheable(bool $cacheable): void
    {
        $this->cacheable = $cacheable;
    }

    protected function doFindOneBy(string  $cacheKey, array $criteria, array $orderBy = null): ?object
    {
        if ($this->isCacheable()) {
            $object = $this->getItem($cacheKey);
            if (!$object) {
                $object = parent::findOneBy($criteria, $orderBy);

                $this->cache($cacheKey, $object);
            }

            return $object;
        }

        return parent::findOneBy($criteria, $orderBy);
    }

    protected function doFindBy(string  $cacheKey, array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        if ($this->isCacheable()) {
            $objects = $this->getItem($cacheKey);
            if (!$objects) {
                $objects = parent::findBy($criteria, $orderBy, $limit, $offset);

                $this->cache($cacheKey, $objects);
            }

            return $objects;
        }

        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    protected function cache(string $key, $item): void
    {
        if (!$this->cache->contains($key)) {
            $this->cache->save($key, $item, 17);
        }
    }

    protected function getItem(string $key)
    {
        $entity = $this->cache->fetch($key);
        if ($entity) {
            $this->_em->merge($entity);
        }

        return $entity;
    }
}

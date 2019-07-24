<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Contract\Repository\CacheableRepositoryInterface;
use Ramsey\Uuid\Uuid;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class Repository implements CacheableRepositoryInterface
{
    private $cache;

    private $cacheable;

    protected $proxy;

    /** @var EntityManager */
    protected $manager;

    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        $this->proxy = new ServiceEntityRepository($registry, $entityClass);
        $this->manager = $registry->getManagerForClass($entityClass);
        $this->cache = new ArrayCache();
        $this->cacheable = false;
    }

    public function find($id): ?object
    {
        if (!Uuid::isValid($id)) {
            return null;
        }

        if (!$this->isCacheable()) {
            return $this->proxy->find($id);
        }

        $entity = $this->getItem($id);
        if (!$entity) {
            $entity = $this->proxy->find($id);

            $this->cache($id, $entity);
        }

        return $entity;
    }

    public function findUniqueBy(array $criteria): ?object
    {
        $filters = $this->manager->getFilters();
        $filterName = sprintf('%s_softdeletable', Application::APP_UNIQUE_NAME);
        if ($filters->isEnabled($filterName)) {
            $filters->disable($filterName);
        }

        return $this->findOneBy(array_merge($criteria));
    }

    public function findAll(): array
    {
        return $this->proxy->findAll();
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
                $object = $this->proxy->findOneBy($criteria, $orderBy);

                $this->cache($cacheKey, $object);
            }

            return $object;
        }

        return $this->proxy->findOneBy($criteria, $orderBy);
    }

    protected function doFindBy(string  $cacheKey, array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        if ($this->isCacheable()) {
            $objects = $this->getItem($cacheKey);
            if (!$objects) {
                $objects = $this->proxy->findBy($criteria, $orderBy, $limit, $offset);

                $this->cache($cacheKey, $objects);
            }

            return $objects;
        }

        return $this->proxy->findBy($criteria, $orderBy, $limit, $offset);
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
            $this->manager->merge($entity);
        }

        return $entity;
    }
}

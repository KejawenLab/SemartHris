<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Cache\CacheHandler;
use KejawenLab\Semart\Skeleton\Entity\EntityEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class AdminController extends AbstractController
{
    private $eventDispatcher;

    private $cacheProvider;

    public function __construct(EventDispatcherInterface $eventDispatcher, CacheHandler $cacheProvider)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->cacheProvider = $cacheProvider;
    }

    /**
     * @param string $key
     * @param mixed  $content
     *
     * @return mixed
     */
    protected function cache(string $key, $content = null)
    {
        if (!$content) {
            $this->cacheProvider->cache($key, $content);
        }

        return $this->cacheProvider->getItem($key);
    }

    protected function isCached(string $key): bool
    {
        return $this->cacheProvider->isCached($key);
    }

    protected function commit(object $entity): void
    {
        $manager = $this->getDoctrine()->getManager();

        $this->eventDispatcher->dispatch(new EntityEvent($manager, $entity));

        $manager->persist($entity);
        $manager->flush();
    }

    protected function remove(object $entity): void
    {
        $manager = $this->getDoctrine()->getManager();

        $this->eventDispatcher->dispatch(new EntityEvent($manager, $entity));

        $manager->remove($entity);
        $manager->flush();
    }
}

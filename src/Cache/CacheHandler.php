<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Cache;

use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CacheHandler
{
    private $provider;

    private $item;

    public function __construct(AdapterInterface $provider)
    {
        $this->provider = $provider;
    }

    public function cache(string $key, $content = null): void
    {
        $item = $this->provider->getItem($key);
        $item->set($content);
        $item->expiresAfter((new \DateInterval('PT17S')));
        $this->provider->save($item);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getItem(string $key)
    {
        if ($this->isCached($key)) {
            return $this->item[$key];
        }

        return null;
    }

    public function isCached(string $key): bool
    {
        $item = $this->provider->getItem($key);
        if ($this->item[$key] = $item->get()) {
            return true;
        }

        return false;
    }
}

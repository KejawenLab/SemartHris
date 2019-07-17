<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Contract\Repository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface CacheableRepositoryInterface
{
    public function isCacheable(): bool;

    public function setCacheable(bool $cacheable): void;
}

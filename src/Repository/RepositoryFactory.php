<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Repository;

use KejawenLab\Semart\Collection\Collection;
use KejawenLab\Semart\Skeleton\Contract\Repository\RepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RepositoryFactory
{
    private $repositories = [];

    public function __construct(array $repositories = [])
    {
        Collection::collect($repositories)
            ->each(function ($value) {
                $this->addRepository($value);
            })
        ;
    }

    public function getRepository(string $repositoryClass): ?RepositoryInterface
    {
        if (!\array_key_exists($repositoryClass, $this->repositories)) {
            return null;
        }

        return $this->repositories[$repositoryClass];
    }

    private function addRepository(RepositoryInterface $repository): void
    {
        $this->repositories[\get_class($repository)] = $repository;
    }
}

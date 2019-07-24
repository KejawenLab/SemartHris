<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Contract\Repository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface RepositoryInterface
{
    public function find(string $id): ?object;

    public function findBy(array $criteria): array;

    public function findOneBy(array $criteria): ?object;

    public function findAll(): array;
}

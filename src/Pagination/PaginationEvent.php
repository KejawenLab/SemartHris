<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Pagination;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PaginationEvent extends Event
{
    private $request;

    private $queryBuilder;

    private $entityClass;

    private $joinAlias = [];

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function getQueryBuilder(): ?QueryBuilder
    {
        return $this->queryBuilder;
    }

    public function setQueryBuilder(QueryBuilder $queryBuilder): void
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function getEntityClass(): ?string
    {
        return $this->entityClass;
    }

    public function setEntityClass(string $entityClass): void
    {
        $this->entityClass = $entityClass;
    }

    public function addJoinAlias(string $field, string $alias): void
    {
        $this->joinAlias[$field] = $alias;
    }

    public function getJoinAlias(string $field): ?string
    {
        if (!\array_key_exists($field, $this->joinAlias)) {
            return null;
        }

        return $this->joinAlias[$field];
    }

    public function getJoinFields(): array
    {
        return array_keys($this->joinAlias);
    }
}

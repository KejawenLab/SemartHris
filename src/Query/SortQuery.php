<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Query;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Semart\Collection\Collection;
use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Pagination\PaginationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SortQuery implements EventSubscriberInterface
{
    private $annotationReader;

    public function __construct(Reader $reader)
    {
        $this->annotationReader = $reader;
    }

    public function apply(PaginationEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request) {
            return;
        }

        if ('' === $sortField = $request->query->get('s', '')) {
            return;
        }

        $sortable = Collection::collect($this->annotationReader->getClassAnnotations(new \ReflectionClass($event->getEntityClass())))
            ->filter(static function ($value) {
                if ($value instanceof Sortable) {
                    return true;
                }

                return false;
            })
            ->toArray()
        ;

        if (empty($sortable)) {
            return;
        }

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $event->getQueryBuilder();
        $sort = sprintf('%s.%s', $event->getJoinAlias('root'), $sortField);
        if (false !== strpos($sortField, '.')) {
            $fields = Collection::collect(explode('.', $sortField));
            $length = $fields->count();

            $fields->each(static function ($value, $key) use (&$sort, $length, $fields, $event, $queryBuilder) {
                if ($key === $length - 1) {
                    return false;
                }

                $alias = $event->getJoinAlias($value);
                if (!$alias) {
                    $random = Application::APP_UNIQUE_NAME;
                    $alias = $random[rand($key, \strlen($random) - 1)];

                    if (0 === $key) {
                        $queryBuilder->leftJoin(sprintf('%s.%s', $event->getJoinAlias('root'), $value), $alias);
                    } else {
                        $queryBuilder->leftJoin(sprintf('%s.%s', $fields->get($key - 1), $value), $alias);
                    }
                }

                $sort = sprintf('%s.%s', $alias, $fields->get($key + 1));

                return true;
            });
        }

        $queryBuilder->addOrderBy($sort, 'a' === $request->query->get('d', 'a') ? 'ASC' : 'DESC');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Application::PAGINATION_EVENT => [['apply', -255]],
        ];
    }
}

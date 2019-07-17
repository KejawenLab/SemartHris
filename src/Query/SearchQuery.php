<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Query;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Semart\Collection\Collection;
use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Pagination\PaginationEvent;
use PHLAK\Twine\Str;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SearchQuery implements EventSubscriberInterface
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

        if ('' === $queryString = $request->query->get('q', '')) {
            return;
        }

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $event->getQueryBuilder();
        $expr = $queryBuilder->expr();
        $searchs = Collection::collect($this->annotationReader->getClassAnnotations(new \ReflectionClass($event->getEntityClass())))
            ->filter(function ($value) {
                if (!$value instanceof Searchable) {
                    return false;
                }

                return true;
            })
            ->map(function ($value) {
                /* @var Searchable $value */
                return $value->getFields();
            })
            ->flatten()
            ->map(function ($value) use ($queryBuilder, $expr, $event, $queryString) {
                if (false !== strpos($value, '.')) {
                    $fields = Collection::collect(explode('.', $value));
                    $fields
                        ->filter(function ($value) use ($event) {
                            return \in_array($value, $event->getJoinFields());
                        })
                        ->each(function ($value, $key) use ($fields, $queryBuilder, $event) {
                            $random = Application::APP_UNIQUE_NAME;
                            $alias = $random[rand($key, \strlen($random) - 1)];

                            if (0 === $key) {
                                $queryBuilder->leftJoin(sprintf('%s.%s', $event->getJoinAlias('root'), $value), $alias);
                            } else {
                                $queryBuilder->leftJoin(sprintf('%s.%s', $fields->get($key - 1), $value), $alias);
                            }

                            $event->addJoinAlias($value, $alias);
                        })
                    ;

                    $length = $fields->count();
                    /** @var string $alias */
                    $alias = $fields->get($length - 2);

                    return $expr->like(sprintf('LOWER(%s.%s)', $event->getJoinAlias($alias), $fields->get($length - 1)), $expr->literal(sprintf('%%%s%%', Str::make($queryString)->lowercase())));
                } else {
                    return $expr->like(sprintf('LOWER(%s.%s)', $event->getJoinAlias('root'), $value), $expr->literal(sprintf('%%%s%%', Str::make($queryString)->lowercase())));
                }
            })
            ->toArray()
        ;

        $queryBuilder->andWhere($expr->orX(...$searchs));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Application::PAGINATION_EVENT => [['apply', 255]],
        ];
    }
}

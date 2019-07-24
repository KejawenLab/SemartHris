<?php
/**
 * This file is part of the Semart HRIS Application.
 *
 * (c) Muhamad Surya Iksanudin <surya.kejawen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use KejawenLab\Semart\Skeleton\Entity\District;
use KejawenLab\Semart\Skeleton\Pagination\PaginationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class DistrictSubscriber implements EventSubscriberInterface
{
    public function filterPagination(PaginationEvent $event) {
        if ($event->getEntityClass() !== District::class) {
            return;
        }

        $filter = $event->getRequest()->query->get('f');
        if ($filter) {
            $queryBuilder = $event->getQueryBuilder();
            $queryBuilder->join(sprintf('%s.province', $event->getJoinAlias('root')), 'p');
            $queryBuilder->andWhere($queryBuilder->expr()->eq('p.id', $queryBuilder->expr()->literal($filter)));
            $event->addJoinAlias('province', 'p');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaginationEvent::class => [['filterPagination']],
        ];
    }
}

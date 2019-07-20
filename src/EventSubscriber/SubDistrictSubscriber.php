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

use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Entity\SubDistrict;
use KejawenLab\Semart\Skeleton\Pagination\PaginationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SubDistrictSubscriber implements EventSubscriberInterface
{
    public function filterPagination(PaginationEvent $event) {
        if ($event->getEntityClass() !== SubDistrict::class) {
            return;
        }

        $filter = $event->getRequest()->query->get('f');
        if ($filter) {
            $queryBuilder = $event->getQueryBuilder();
            $queryBuilder->join(sprintf('%s.district', $event->getJoinAlias('root')), 'd');
            $queryBuilder->andWhere($queryBuilder->expr()->eq('d.id', $queryBuilder->expr()->literal($filter)));
            $event->addJoinAlias('district', 'd');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Application::PAGINATION_EVENT => [['filterPagination']],
        ];
    }
}

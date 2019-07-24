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

use KejawenLab\Semart\Skeleton\Component\Contract\Organization\OrganizationInterface;
use KejawenLab\Semart\Skeleton\Entity\Organization;
use KejawenLab\Semart\Skeleton\Pagination\PaginationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OrganizationSubscriber implements EventSubscriberInterface
{
    public function filterPagination(PaginationEvent $event)
    {
        if (Organization::class !== $event->getEntityClass()) {
            return;
        }

        $level = (int) $event->getRequest()->query->get('l', OrganizationInterface::LEVEL_DIVISION);

        $queryBuilder = $event->getQueryBuilder();
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.level', $queryBuilder->expr()->literal($level)));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaginationEvent::class => [['filterPagination']],
        ];
    }
}

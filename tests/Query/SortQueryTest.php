<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Query;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\QueryBuilder;
use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Pagination\PaginationEvent;
use KejawenLab\Semart\Skeleton\Query\Sortable;
use KejawenLab\Semart\Skeleton\Query\SortQuery;
use KejawenLab\Semart\Skeleton\Tests\TestCase\DatabaseTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SortQueryTest extends DatabaseTestCase
{
    public function testApply()
    {
        $queryBuilder = new QueryBuilder(static::$entityManager);
        $queryBuilder->select('o');
        $queryBuilder->from(User::class, 'o');

        $request = Request::createFromGlobals();
        $request->query->set('s', 'username');

        $event = new PaginationEvent();
        $event->setRequest($request);
        $event->setEntityClass(User::class);
        $event->setQueryBuilder($queryBuilder);
        $event->addJoinAlias('root', 'o');

        $readerMock = $this->getMockBuilder(Reader::class)->disableOriginalConstructor()->getMock();
        $readerMock
            ->expects($this->once())
            ->method('getClassAnnotations')
            ->willReturn([new Sortable(['fields' => ['username', 'fullName']])])
        ;

        $this->assertNull((new SortQuery($readerMock))->apply($event));
        $this->assertNotFalse(strpos($queryBuilder->getQuery()->getSQL(), 'ORDER'));
    }

    public function testGetSubscribedEvents()
    {
        $this->assertCount(1, SortQuery::getSubscribedEvents());
    }
}

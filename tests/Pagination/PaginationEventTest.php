<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Pagination;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Pagination\PaginationEvent;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PaginationEventTest extends KernelTestCase
{
    public function testFilterPagination()
    {
        static::bootKernel();

        $paginationEvent = new PaginationEvent();
        $paginationEvent->setRequest(Request::createFromGlobals());
        $paginationEvent->setQueryBuilder(new QueryBuilder(static::$container->get('doctrine.orm.entity_manager')));
        $paginationEvent->setEntityClass(User::class);
        $paginationEvent->addJoinAlias('root', Paginator::ROOT_ALIAS);

        $this->assertInstanceOf(Event::class, $paginationEvent);
        $this->assertInstanceOf(Request::class, $paginationEvent->getRequest());
        $this->assertInstanceOf(QueryBuilder::class, $paginationEvent->getQueryBuilder());
        $this->assertEquals(User::class, $paginationEvent->getEntityClass());
        $this->assertEquals('o', $paginationEvent->getJoinAlias('root'));
        $this->assertNull($paginationEvent->getJoinAlias('not_exist_field'));
        $this->assertContains('root', $paginationEvent->getJoinFields());
    }
}

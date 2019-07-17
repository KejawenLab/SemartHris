<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Pagination;

use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Pagination\Paginator;
use KejawenLab\Semart\Skeleton\Setting\SettingService;
use KejawenLab\Semart\Skeleton\Tests\TestCase\DatabaseTestCase;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PaginatorTest extends DatabaseTestCase
{
    public function testPaginate()
    {
        static::bootKernel();

        $requestStackMock = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestStackMock
            ->expects($this->atLeastOnce())
            ->method('getCurrentRequest')
            ->willReturn(Request::createFromGlobals())
        ;

        $settingServiceMock = $this->getMockBuilder(SettingService::class)->disableOriginalConstructor()->getMock();
        $settingServiceMock
            ->expects($this->at(0))
            ->method('getValue')
            ->willReturn(Paginator::PER_PAGE)
        ;

        $paginator = new Paginator(
            static::$container->get('doctrine.orm.entity_manager'),
            static::$container->get('knp_paginator'),
            $requestStackMock,
            static::$container->get('event_dispatcher'),
            $settingServiceMock
        );

        $this->assertInstanceOf(PaginationInterface::class, $paginator->paginate(User::class));
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Repository;

use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Repository\MenuRepository;
use KejawenLab\Semart\Skeleton\Repository\Repository;
use KejawenLab\Semart\Skeleton\Tests\TestCase\DatabaseTestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuRepositoryTest extends DatabaseTestCase
{
    public function testRepository()
    {
        static::bootKernel();

        $repository = new MenuRepository(static::$container->get('doctrine'));

        $this->assertInstanceOf(Repository::class, $repository);

        /** @var Menu $menu */
        $menu = $repository->findByCode('SETTING');

        $this->assertEquals('SETTING', $menu->getCode());
        $this->assertNull($repository->findByCode(static::NOT_FOUND));
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Repository;

use KejawenLab\Semart\Skeleton\Entity\Role;
use KejawenLab\Semart\Skeleton\Repository\GroupRepository;
use KejawenLab\Semart\Skeleton\Repository\MenuRepository;
use KejawenLab\Semart\Skeleton\Repository\Repository;
use KejawenLab\Semart\Skeleton\Repository\RoleRepository;
use KejawenLab\Semart\Skeleton\Tests\TestCase\DatabaseTestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RoleRepositoryTest extends DatabaseTestCase
{
    public function testFindRole()
    {
        static::bootKernel();

        $repository = new RoleRepository(static::$container->get('doctrine'));

        $this->assertInstanceOf(Repository::class, $repository);

        $group = (new GroupRepository(static::$container->get('doctrine')))->findOneBy(['code' => 'SPRADM']);
        $menu = (new MenuRepository(static::$container->get('doctrine')))->findByCode('SETTING');

        $role = $repository->findRole($group, $menu);

        $this->assertInstanceOf(Role::class, $role);

        $this->assertNull($repository->persist($role));
        $this->assertNull($repository->commit());
    }

    public function testFindParentMenuByGroup()
    {
        static::bootKernel();

        $repository = new RoleRepository(static::$container->get('doctrine'));

        $this->assertInstanceOf(Repository::class, $repository);

        $group = (new GroupRepository(static::$container->get('doctrine')))->findOneBy(['code' => 'SPRADM']);

        $this->assertGreaterThanOrEqual(0, \count($repository->findParentMenuByGroup($group)));
    }

    public function testFindRolesByGroup()
    {
        static::bootKernel();

        $repository = new RoleRepository(static::$container->get('doctrine'));

        $this->assertInstanceOf(Repository::class, $repository);

        $group = (new GroupRepository(static::$container->get('doctrine')))->findOneBy(['code' => 'SPRADM']);

        $this->assertGreaterThanOrEqual(0, \count($repository->findRolesByGroup($group)));
    }

    public function testFindChildMenuByGroupAndMenu()
    {
        static::bootKernel();

        $repository = new RoleRepository(static::$container->get('doctrine'));

        $this->assertInstanceOf(Repository::class, $repository);

        $group = (new GroupRepository(static::$container->get('doctrine')))->findOneBy(['code' => 'SPRADM']);
        $menu = (new MenuRepository(static::$container->get('doctrine')))->findByCode('ADMIN');

        $this->assertGreaterThanOrEqual(0, \count($repository->findChildMenuByGroupAndMenu($group, $menu)));
    }
}

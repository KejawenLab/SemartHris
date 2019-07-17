<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Security\Service;

use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Repository\GroupRepository;
use KejawenLab\Semart\Skeleton\Repository\MenuRepository;
use KejawenLab\Semart\Skeleton\Repository\RoleRepository;
use KejawenLab\Semart\Skeleton\Security\Service\RoleService;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RoleServiceTest extends TestCase
{
    private $roleService;

    public function setUp()
    {
        $menus = [new Menu(), new Menu()];
        $groups = [new Group(), new Group()];

        $roleRepositoryMock = $this->getMockBuilder(RoleRepository::class)->disableOriginalConstructor()->getMock();
        $roleRepositoryMock
            ->method('persist')
            ->withAnyParameters()
        ;
        $roleRepositoryMock
            ->method('commit')
        ;

        $menuRepositoryMock = $this->getMockBuilder(MenuRepository::class)->disableOriginalConstructor()->getMock();
        $menuRepositoryMock
            ->method('findAll')
            ->willReturn($menus)
        ;

        $groupRepositoryMock = $this->getMockBuilder(GroupRepository::class)->disableOriginalConstructor()->getMock();
        $groupRepositoryMock
            ->method('findAll')
            ->willReturn($groups)
        ;

        $this->roleService = new RoleService($roleRepositoryMock, $menuRepositoryMock, $groupRepositoryMock);
    }

    public function testAssignToGroup()
    {
        $this->assertNull($this->roleService->assignToGroup(new Group()));
    }

    public function testAssignToMenu()
    {
        $this->assertNull($this->roleService->assignToMenu(new Menu()));
    }
}

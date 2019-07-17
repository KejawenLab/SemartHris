<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Generator;

use KejawenLab\Semart\Skeleton\Generator\PermissionGenerator;
use KejawenLab\Semart\Skeleton\Menu\MenuService;
use KejawenLab\Semart\Skeleton\Security\Service\GroupService;
use KejawenLab\Semart\Skeleton\Security\Service\RoleService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PermissionGeneratorTest extends KernelTestCase
{
    public function setUp()
    {
        static::bootKernel();
    }

    public function testGenerate()
    {
        $groupServiceMock = $this->getMockBuilder(GroupService::class)->disableOriginalConstructor()->getMock();
        $menuServiceMock = $this->getMockBuilder(MenuService::class)->disableOriginalConstructor()->getMock();
        $roleServiceMock = $this->getMockBuilder(RoleService::class)->disableOriginalConstructor()->getMock();

        $reflection = new \ReflectionClass(Stub::class);

        $generator = new PermissionGenerator($groupServiceMock, $menuServiceMock, $roleServiceMock);
        $this->assertNull($generator->generate($reflection));
    }
}

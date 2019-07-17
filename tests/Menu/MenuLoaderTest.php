<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Menu;

use KejawenLab\Semart\Skeleton\Cache\CacheHandler;
use KejawenLab\Semart\Skeleton\Entity\Group;
use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Menu\MenuLoader;
use KejawenLab\Semart\Skeleton\Repository\RoleRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuLoaderTest extends TestCase
{
    /**
     * @dataProvider seed
     */
    public function testGetParentMenu(MockObject $roleRepository, MockObject $tokenStorage, CacheHandler $cacheHandler, Group $group)
    {
        $roleRepository
            ->expects($this->once())
            ->method('findParentMenuByGroup')
            ->with($group)
            ->willReturn([])
        ;

        $menuLoader = new MenuLoader($roleRepository, $tokenStorage, $cacheHandler);

        $this->assertEmpty($menuLoader->getParentMenu());
    }

    /**
     * @dataProvider seed
     */
    public function testChildMenu(MockObject $roleRepository, MockObject $tokenStorage, CacheHandler $cacheHandler, Group $group)
    {
        $menu = new Menu();

        $roleRepository
            ->expects($this->once())
            ->method('findChildMenuByGroupAndMenu')
            ->with($group, $menu)
            ->willReturn([])
        ;

        $menuLoader = new MenuLoader($roleRepository, $tokenStorage, $cacheHandler);

        $this->assertFalse($menuLoader->hasChildMenu($menu));
        $this->assertEmpty($menuLoader->getChildMenu($menu));
    }

    public function seed()
    {
        $group = new Group();

        $user = new User();
        $user->setGroup($group);

        $tokenMock = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        $tokenMock
            ->expects($this->atLeastOnce())
            ->method('getUser')
            ->willReturn($user)
        ;

        $tokenStorageMock = $this->getMockBuilder(TokenStorageInterface::class)->disableOriginalConstructor()->getMock();
        $tokenStorageMock
            ->expects($this->atLeastOnce())
            ->method('getToken')
            ->willReturn($tokenMock)
        ;
        $adapter = new ArrayAdapter();
        $cacheHandler = new CacheHandler($adapter);

        $roleRepositoryMock = $this->getMockBuilder(RoleRepository::class)->disableOriginalConstructor()->getMock();

        return [
            [$roleRepositoryMock, $tokenStorageMock, $cacheHandler, $group],
        ];
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Pagination;

use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Menu\MenuExtension;
use KejawenLab\Semart\Skeleton\Menu\MenuLoader;
use KejawenLab\Semart\Skeleton\Menu\MenuService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuExtensionTest extends TestCase
{
    /**
     * @dataProvider seed
     */
    public function testRenderMenu(MockObject $menuLoader, MockObject $menuService, MockObject $urlGenerator)
    {
        $menu1 = new Menu();
        $menu1->setCode('a');
        $menu1->setRouteName('parent');
        $menu1->setName('PARENT');

        $menu2 = new Menu();
        $menu2->setParent($menu1);
        $menu2->setCode('b');
        $menu2->setName('CHILD');
        $menu2->setRouteName('child');

        $menuLoader
            ->expects($this->once())
            ->method('getParentMenu')
            ->withAnyParameters()
            ->willReturn([$menu1])
        ;

        $menuLoader
            ->expects($this->once())
            ->method('hasChildMenu')
            ->with($menu1)
            ->willReturn(true)
        ;

        $menuLoader
            ->expects($this->once())
            ->method('getChildMenu')
            ->with($menu1)
            ->willReturn([$menu2])
        ;

        $urlGenerator
            ->expects($this->once())
            ->method('generate')
            ->withAnyParameters()
            ->willReturn('#')
        ;

        $menuExtension = new MenuExtension($menuLoader, $menuService, $urlGenerator);

        $this->assertRegexp('/(PARENT|CHILD)/', $menuExtension->renderMenu());
    }

    /**
     * @dataProvider seed
     */
    public function testFindMenu(MenuLoader $menuLoader, MockObject $menuService, UrlGeneratorInterface $urlGenerator)
    {
        $menu = new Menu();

        $menuService
            ->expects($this->once())
            ->method('getMenuByCode')
            ->withAnyParameters()
            ->willReturn($menu)
        ;

        $menuExtension = new MenuExtension($menuLoader, $menuService, $urlGenerator);

        $this->assertSame($menu, $menuExtension->findMenu('test'));
    }

    /**
     * @dataProvider seed
     */
    public function testGetFunctions(MenuLoader $menuLoader, MenuService $menuService, UrlGeneratorInterface $urlGenerator)
    {
        $this->assertCount(2, (new MenuExtension($menuLoader, $menuService, $urlGenerator))->getFunctions());
    }

    public function seed()
    {
        $menuLoaderMock = $this->getMockBuilder(MenuLoader::class)->disableOriginalConstructor()->getMock();

        $menuServiceMock = $this->getMockBuilder(MenuService::class)->disableOriginalConstructor()->getMock();

        $urlGeneratorMock = $this->getMockBuilder(UrlGeneratorInterface::class)->disableOriginalConstructor()->getMock();

        return [
            [$menuLoaderMock, $menuServiceMock, $urlGeneratorMock],
        ];
    }
}
